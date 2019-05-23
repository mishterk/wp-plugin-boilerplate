<?php


namespace PdkPluginBoilerplate\Framework\Container;


class Plugin extends Application {


	protected $bootstrap_hook = 'plugins_loaded';

	protected $boot_hook = 'plugins_loaded';

	protected $init_hook = 'init';


	/**
	 * @param $plugin_dir
	 * @param $plugin_url
	 */
	public function __construct( $plugin_dir, $plugin_url ) {
		$this->bind( 'url.base', $plugin_url );

		parent::__construct( $plugin_dir );
	}


}