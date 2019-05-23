<?php


namespace PdkPluginBoilerplate\Framework\Container;


/**
 * NOTE: This is WIP and is untested. We need to start building a theme foundation in order to work on this
 *
 * Class Theme
 * @package PdkPluginBoilerplate\Framework\Container
 */
class Theme extends Application {


	protected $bootstrap_hook = 'after_setup_theme';

	protected $boot_hook = 'after_setup_theme';

	protected $init_hook = 'init';


	/**
	 * @param $theme_dir
	 * @param $theme_url
	 */
	public function __construct( $theme_dir, $theme_url ) {
		$this->bind( 'url.base', $theme_url );

		// todo - consider these
		//  get_template_directory();
		//  get_template_directory_uri();
		//  get_stylesheet_directory();
		//  get_stylesheet_directory_uri();

		parent::__construct( $theme_dir );
	}


}