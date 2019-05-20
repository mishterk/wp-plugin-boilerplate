<?php


namespace PdkPluginBoilerplate\Framework\Container;


class Plugin extends Application {


	protected $base_dir;
	protected $base_url;


	/**
	 * @param $base_dir
	 * @param $base_url
	 */
	public function __construct( $base_dir, $base_url ) {
		$this->base_dir = $base_dir;
		$this->base_url = $base_url;

		$this->bind( 'base_dir', $base_dir );
		$this->bind( 'base_url', $base_url );

		parent::__construct( $base_dir );
	}


	public function init() {
		add_action( 'plugins_loaded', [ $this, '_on_plugins_loaded' ] );
		add_action( 'init', [ $this, '_on_init' ] );
	}


	public function _on_plugins_loaded() {
		$this->register_providers();
		$this->call_method_on_providers( 'plugins_loaded' );
	}


	public function _on_init() {
		$this->call_method_on_providers( 'init' );
	}


	/**
	 * Register all providers defined in the providers config
	 *
	 * @throws \Exception
	 */
	protected function register_providers() {
		$class_names = $this->make( 'config' )->get( 'providers', [] );

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