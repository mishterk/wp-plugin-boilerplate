<?php


namespace PdkPluginBoilerplate\Framework\Container;


class Plugin extends Application {


	/**
	 * Define which hook to run the bootstrap process on.
	 *
	 * @var string|bool Hook name or FALSE to prevent hooked bootstrap.
	 */
	protected $bootstrap_hook = 'plugins_loaded';


	/**
	 * Define which hook to run the boot process on.
	 *
	 * @var string|bool Hook name or FALSE to prevent hooked bootstrap.
	 */
	protected $boot_hook = 'plugins_loaded';


	/**
	 * Define which hook to run the init process on.
	 *
	 * @var string|bool Hook name or FALSE to prevent hooked bootstrap.
	 */
	protected $init_hook = 'init';


	/**
	 * @param $plugin_dir
	 * @param $plugin_url
	 */
	public function __construct( $plugin_dir, $plugin_url ) {
		$this->bind( 'plugin.dir', $plugin_dir );
		$this->bind( 'plugin.url', $plugin_url );

		parent::__construct( $plugin_dir );
	}


}