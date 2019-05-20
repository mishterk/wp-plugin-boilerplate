<?php


namespace PdkPluginBoilerplate\Framework\Container;


use PdkPluginBoilerplate\Framework\Providers\ConfigServiceProvider;
use PdkPluginBoilerplate\Framework\Providers\ServiceProviderBase;
use PdkPluginBoilerplate\Framework\Traits\Singleton;


class Application extends Container {


	use Singleton;


	/**
	 * @var ServiceProviderBase[]
	 */
	protected $registered_providers = [];


	public function __construct( $base_path = null ) {
		if ( $base_path ) {
			$this->set_base_path( $base_path );
		}

		$this->register_base_bindings();
		$this->register_directory_bindings();
		$this->register_base_providers();
	}


	public function set_base_path( $path ) {
		$this->bind( 'path.base', rtrim( $path, '\/' ) );
	}


	public function register_provider( ServiceProviderBase $provider ) {
		$provider->register( $this );
		$this->registered_providers[ get_class( $provider ) ] = $provider;
	}


	protected function register_base_bindings() {
		self::$_instance = $this;
		$this->singleton( 'app', $this );
		$this->singleton( Container::class, $this );
	}


	protected function register_directory_bindings() {
		$base_path = $this->make( 'path.base' );
		$this->singleton( 'path.config', "$base_path/config" );
	}


	protected function register_base_providers() {
		$this->register_provider( new ConfigServiceProvider() );
	}


	/**
	 * Loop through all registered providers and call the defined method if it exists
	 *
	 * @param $method
	 */
	protected function call_method_on_providers( $method ) {
		foreach ( $this->registered_providers as $provider ) {
			if ( method_exists( $provider, $method ) ) {
				$provider->$method();
			}
		}
	}


	/**
	 * Register all providers defined in the providers config
	 *
	 * @throws \Exception
	 */
	protected function register_providers() {
		$class_names = $this['config']->get( 'providers', [] );

		foreach ( $class_names as $class_name ) {
			if ( class_exists( $class_name ) ) {
				$this->singleton( $class_name );
				$this->register_provider( $this->make( $class_name ) );
			}
		}
	}


}