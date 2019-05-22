<?php


namespace PdkPluginBoilerplate\Framework\Container;


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
	 * todo - consider moving to Application class and making the hooks configurable in Plugin/Theme classes
	 * Initialises anything that needs handling before all providers are registered but not necessarily
	 * on init.
	 */
	public function _on_plugins_loaded_first() {
		$this->bootstrap();
	}


	/**
	 * todo - consider moving to Application class and making the hooks configurable in Plugin/Theme classes
	 * Invoked on WordPress' 'plugins_loaded' hook. This is where we register our providers and provide them
	 * the opportunity to run their own actions on the same hook without having to hook themselves.
	 *
	 * @throws \Exception
	 */
	public function _on_plugins_loaded() {
		$this->boot();
	}


	/**
	 * todo - consider moving to Application class and making the hooks configurable in Plugin/Theme classes
	 * Invoked on WordPress' 'init' hook. This is where we allow our service providers the opportunity to run their
	 * own actions on the same hook without having to hook themselves.
	 */
	public function _on_init() {
		parent::init();
	}


}