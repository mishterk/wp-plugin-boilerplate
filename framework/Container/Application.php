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
			$this->bind( 'path.base', rtrim( $base_path, '\/' ) );
		}

		$this->register_base_bindings();
		$this->register_directory_bindings();
		$this->register_base_providers();
	}


	/**
	 * Register a service provider with the container.
	 *
	 * @param ServiceProviderBase $provider
	 */
	public function register_provider( ServiceProviderBase $provider ) {
		$provider->register( $this );
		$this->registered_providers[ get_class( $provider ) ] = $provider;
	}


	protected function register_base_bindings() {
		self::$_instance = $this;
		$this->singleton( 'app', $this );
		$this->singleton( Container::class, $this );
	}


	/**
	 * @throws \Exception
	 */
	protected function register_directory_bindings() {
		$base_path = $this->make( 'path.base' );

		$this->singleton( 'path.app', "$base_path/app" );
		$this->singleton( 'path.config', "$base_path/config" );
		$this->singleton( 'path.assets', "$base_path/assets" );
		$this->singleton( 'path.framework', "$base_path/framework" );
		$this->singleton( 'path.templates', "$base_path/templates" );
		$this->singleton( 'path.tests', "$base_path/tests" );
	}


	/**
	 * Register any core, non-optional service providers that need to be registered early.
	 */
	protected function register_base_providers() {
		$this->register_provider( new ConfigServiceProvider() );
	}


	/**
	 * Boot any service provider initialisation that needs to be in place before other service providers
	 * are registered.
	 */
	protected function boot_base_providers() {
		$this->registered_providers[ ConfigServiceProvider::class ]->load_configuration_files();
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