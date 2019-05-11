<?php


namespace PdkPluginBoilerplate\Framework\Foundation;


use Closure;
use InvalidArgumentException;


class Container {

	/**
	 * The raw bindings. e.g; ['some.key' => 'some value', 'another.key' => function(){…} ]
	 *
	 * @var array
	 */
	protected $bindings = [];

	/**
	 * An array of key/bool pairs for tracking which keys are singletons. e.g; [ 'some.key' => bool(TRUE) ]
	 *
	 * @var array
	 */
	protected $singletons = [];

	/**
	 * @var array
	 */
	protected $instances = [];


	public function bind( $key, $concrete ) {
		if ( $concrete === null ) {
			/*
			 * todo - might be worth supporting null values for auto class resolution. See Laravel's container for some
			 *  tips on how we could go about this.
			 */
			return;
		}

		$this->bindings[ $key ] = $concrete;
	}


	/**
	 * @param $key
	 *
	 * @return mixed
	 */
	public function make( $key ) {
		if ( ! $this->is_bound( $key ) ) {
			throw new InvalidArgumentException( "Container binding for key '$key' not found." );
		}

		$resolved = $this->resolve_binding( $this->bindings[ $key ] );

		// if dealing with a singleton, set the instance if not already set, then return the instance
		if ( $this->is_singleton( $key ) ) {
			return $this->instances[ $key ] ?? $this->instance( $key, $resolved );
		}

		return $resolved;
	}


	public function singleton( $key, $concrete ) {
		$this->singletons[ $key ] = true;
		$this->bind( $key, $concrete );
	}


	public function instance( $key, $value ) {
		return $this->instances[ $key ] = $value;
	}


	public function protected( $key, $concrete ) {

	}


	public function extend() {

	}


	protected function is_bound( $key ) {
		return isset( $this->bindings[ $key ] );
	}


	protected function is_singleton( $key ) {
		return isset( $this->singletons[ $key ] );
	}


	protected function resolve_binding( $binding ) {
		return ( $binding instanceof Closure )
			? $binding()
			: $binding;
	}


}