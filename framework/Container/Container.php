<?php


namespace PdkPluginBoilerplate\Framework\Container;


use Closure;
use InvalidArgumentException;
use RuntimeException;


/**
 * Class Container
 * @package PdkPluginBoilerplate\Framework\Foundation
 */
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
	 * An array of key/bool pairs for tracking which keys are factories. e.g; [ 'some.key' => bool(TRUE) ]
	 *
	 * @var array
	 */
	protected $factories = [];


	/**
	 * @var array
	 */
	protected $instances = [];


	/**
	 * An array of key/bool pairs for tracking which keys are protected. Protected bindings cannot be overriden.
	 * e.g; [ 'some.key' => bool(TRUE) ]
	 *
	 * @var array
	 */
	protected $protected = [];


	/**
	 * @param $key
	 * @param $concrete
	 */
	public function bind( $key, $concrete ) {
		if ( $this->is_protected( $key ) and $this->is_bound( $key ) ) {
			throw new RuntimeException( "Key '$key' is a protected container binding and cannot be overridden." );
		}

		if ( $concrete === null ) {
			// todo - might be worth supporting null values for auto class resolution. See Laravel's container for some
			//  tips on how we could go about this.
			throw new InvalidArgumentException( "NULL is not a supported container binding value. Value for key '$key' needs to be changed." );
		}

		$this->bindings[ $key ] = $this->enclose( $concrete );
	}


	/**
	 * @param $key
	 *
	 * @return mixed
	 */
	public function make( $key ) {
		$resolved = $this->resolve( $key );

		if ( $this->is_singleton( $key ) ) {
			return $this->instances[ $key ] ?? $this->cache_instance( $key, $resolved );
		}

		return $resolved;
	}


	/**
	 * @param $key
	 * @param $concrete
	 */
	public function singleton( $key, $concrete ) {
		$this->singletons[ $key ] = true;
		$this->bind( $key, $concrete );
	}


	/**
	 * @param $key
	 * @param $concrete
	 */
	public function protected( $key, $concrete ) {
		$this->protected[ $key ] = true;
		$this->bind( $key, $concrete );
	}


	/**
	 * @param $key
	 * @param $concrete
	 */
	public function factory( $key, $concrete ) {
		$this->factories[ $key ] = true;
		$this->bind( $key, $concrete );
	}


	/**
	 * Extend an existing binding. This will wrap the existing binding in the supplied closure which will be invoked
	 * after the existing binding effectively allowing modification of the instantiated value immediately after it is
	 * created.
	 *
	 * @param string $key
	 * @param Closure $closure
	 */
	public function extend( $key, Closure $closure ) {
		$binding = $this->get_bound_or_fail( $key );

		if ( ! is_callable( $binding ) ) {
			throw new InvalidArgumentException( "Container binding for key '$key' is not callable and cannot be extended." );
		}

		$extended = function ( $container ) use ( $closure, $binding ) {
			return $closure( $binding( $container ), $closure );
		};

		$this->bind( $key, $extended );
	}


	/**
	 * @param $key
	 */
	public function unbind( $key ) {
		unset(
			$this->bindings[ $key ],
			$this->singletons[ $key ],
			$this->factories[ $key ],
			$this->protected[ $key ],
			$this->instances[ $key ]
		);
	}


	/**
	 * @param $key
	 *
	 * @return bool
	 */
	public function is_bound( $key ) {
		return isset( $this->bindings[ $key ] );
	}


	/**
	 * @param $key
	 *
	 * @return bool
	 */
	public function is_singleton( $key ) {
		return isset( $this->singletons[ $key ] );
	}


	/**
	 * @param $key
	 *
	 * @return bool
	 */
	public function is_protected( $key ) {
		return isset( $this->protected[ $key ] );
	}


	/**
	 * @param $key
	 *
	 * @return bool
	 */
	public function is_factory( $key ) {
		return isset( $this->factories[ $key ] );
	}


	/**
	 * @param mixed $offset
	 *
	 * @return bool
	 */
	public function offsetExists( $offset ) {
		return $this->is_bound( $offset );
	}


	/**
	 * @param mixed $offset
	 *
	 * @return mixed|null
	 */
	public function offsetGet( $offset ) {
		return $this->is_bound( $offset ) ? $this->make( $offset ) : null;
	}


	/**
	 * @param mixed $offset
	 * @param mixed $value
	 */
	public function offsetSet( $offset, $value ) {
		$this->bind( $offset, $value );
	}


	/**
	 * @param mixed $offset
	 */
	public function offsetUnset( $offset ) {
		$this->unbind( $offset );
	}


	/**
	 * Wrap anything that isn't a closure in a closure
	 *
	 * @param $concrete
	 *
	 * @return Closure
	 */
	protected function enclose( $concrete ) {
		if ( $concrete instanceof Closure ) {
			return $concrete;
		}

		return function ( Container $container ) use ( $concrete ) {
			return $concrete;
		};
	}


	/**
	 * Cache an instance for reuse on subsequent requests to the container
	 *
	 * @param $key
	 * @param $instance
	 *
	 * @return mixed
	 */
	protected function cache_instance( $key, $instance ) {
		return $this->instances[ $key ] = $instance;
	}


	/**
	 * @param $key
	 *
	 * @return mixed
	 */
	protected function get_bound_or_fail( $key ) {
		if ( ! $this->is_bound( $key ) ) {
			throw new InvalidArgumentException( "Container binding for key '$key' not found." );
		}

		return $this->bindings[ $key ];
	}


	// todo - auto class resolution by reflection

	/**
	 * @param $key
	 *
	 * @return mixed
	 */
	protected function resolve( $key ) {
		$binding = $this->get_bound_or_fail( $key );

		return ( $binding instanceof Closure )
			? $binding( $this )
			: $binding;
	}


}