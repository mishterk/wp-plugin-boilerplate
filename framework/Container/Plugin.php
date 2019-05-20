<?php


namespace PdkPluginBoilerplate\Framework\Container;


use PdkPluginBoilerplate\Framework\Providers\ConfigServiceProvider;


class Plugin extends Application {


	/**
	 * @param $plugin_dir
	 * @param $plugin_url
	 */
	public function __construct( $plugin_dir, $plugin_url ) {
		$this->bind( 'plugin.dir', $plugin_dir );
		$this->bind( 'plugin.url', $plugin_url );

		parent::__construct( $plugin_dir );
	}


	public function init() {
		add_action( 'plugins_loaded', [ $this, '_on_plugins_loaded_first' ], 1 );
		add_action( 'plugins_loaded', [ $this, '_on_plugins_loaded' ] );
		add_action( 'init', [ $this, '_on_init' ] );
	}


	/**
	 * Initialises anything that needs handling before all providers are registered but not necessarily
	 * on init.
	 */
	public function _on_plugins_loaded_first() {
		$this->registered_providers[ ConfigServiceProvider::class ]->load_configuration_files();
	}


	/**
	 * Invoked on WordPress' 'plugins_loaded' hook. This is where we register our providers and provide them
	 * the opportunity to run their own actions on the same hook without having to hook themselves.
	 *
	 * @throws \Exception
	 */
	public function _on_plugins_loaded() {
		$this->register_providers();
		$this->call_method_on_providers( 'plugins_loaded' );
	}


	/**
	 * Invoked on WordPress' 'init' hook. This is where we allow our service providers the opportunity to run their
	 * own actions on the same hook without having to hook themselves.
	 */
	public function _on_init() {
		$this->call_method_on_providers( 'init' );
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


}