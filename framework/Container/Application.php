<?php


namespace PdkPluginBoilerplate\Framework\Container;


use PdkPluginBoilerplate\Framework\Providers\ConfigServiceProvider;
use PdkPluginBoilerplate\Framework\Providers\ServiceProviderBase;
use PdkPluginBoilerplate\Framework\Traits\Singleton;


class Application extends Container {


	use Singleton;


	protected $base_path;


	/**
	 * @var ServiceProviderBase[]
	 */
	protected $registered_providers = [];


	public function __construct( $base_path = null ) {
		if ( $base_path ) {
			$this->set_base_path( $base_path );
		}

		$this->register_base_bindings();
		$this->register_base_providers();
	}


	public function set_base_path( $path ) {
		$this->base_path = rtrim( $path, '\/' );
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


	protected function register_base_providers() {
		// todo - figure out how/where best to register config base provider
		$this->register_provider( new ConfigServiceProvider() );
	}


}