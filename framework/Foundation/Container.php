<?php


namespace PdkPluginBoilerplate\Framework\Foundation;


use Closure;
use InvalidArgumentException;


class Container implements \ArrayAccess {


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
			// todo - might be worth supporting null values for auto class resolution. See Laravel's container for some
			//  tips on how we could go about this.
			return;
		}

		if ( ! $concrete instanceof Closure ) {
			$concrete = function () use ( $concrete ) {
				return $concrete;
			};
		}

		$this->bindings[ $key ] = $concrete;
	}


	public function unbind( $key ) {
		unset(
			$this->bindings[ $key ],
			$this->singletons[ $key ],
			$this->instances[ $key ]
		);
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

		$resolved = $this->resolve( $key );

		if ( $this->is_singleton( $key ) ) {
			return $this->instances[ $key ] ?? $this->cache_instance( $key, $resolved );
		}

		return $resolved;
	}


	public function singleton( $key, $concrete ) {
		$this->singletons[ $key ] = true;
		$this->bind( $key, $concrete );
	}


	public function protected( $key, $concrete ) {

	}


	public function factory( $key, $concrete ) {

	}


	public function extend() {

	}


	public function offsetExists( $offset ) {
		return $this->is_bound( $offset );
	}


	public function offsetGet( $offset ) {
		return $this->is_bound( $offset ) ? $this->make( $offset ) : null;
	}


	public function offsetSet( $offset, $value ) {
		$this->bind( $offset, $value );
	}


	public function offsetUnset( $offset ) {
		$this->unbind( $offset );
	}


	protected function cache_instance( $key, $value ) {
		return $this->instances[ $key ] = $value;
	}


	protected function is_bound( $key ) {
		return isset( $this->bindings[ $key ] );
	}


	protected function is_singleton( $key ) {
		return isset( $this->singletons[ $key ] );
	}


	protected function is_factory( $key ) {
		// todo
	}


	protected function resolve( $key ) {
		$binding = $this->bindings[ $key ] ?? null;

		return ( $binding instanceof Closure )
			? $binding( $this )
			: $binding;
	}


}