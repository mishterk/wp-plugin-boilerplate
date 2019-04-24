<?php


namespace PdkPluginBoilerplate;


use PdkPluginBoilerplate\Framework\AutoLoader;
use PdkPluginBoilerplate\Framework\Contracts\Initable;
use PdkPluginBoilerplate\View\View;


class Plugin {


	private $components = [];


	public function init() {
		$this->init_autoloader();
		$this->init_view_system();
		$this->init_initables( $this->components );

		// todo - move the script loader
		add_action( 'wp_enqueue_scripts', function () {
			wp_enqueue_script( 'pdkpluginbp', PDK_PLUGIN_BOILERPLATE_PLUGIN_URL . 'assets/build/js/app.js', [ 'jquery' ], PDK_PLUGIN_BOILERPLATE_PLUGIN_VERSION, true );
		} );
	}


	private function init_autoloader() {
		require_once PDK_PLUGIN_BOILERPLATE_PLUGIN_DIR . '/framework/AutoLoader.php';
		$autoloader = new AutoLoader();
		$autoloader->register();
		$autoloader->addNamespace( 'PdkPluginBoilerplate', PDK_PLUGIN_BOILERPLATE_PLUGIN_DIR . 'app' );
		$autoloader->addNamespace( 'PdkPluginBoilerplate\\Framework', PDK_PLUGIN_BOILERPLATE_PLUGIN_DIR . 'framework' );
	}


	private function init_view_system() {
		View::init();
	}


	private function init_initables( $initables ) {
		foreach ( $initables as $initable ) {
			/** @var \PdkPluginBoilerplate\Framework\Contracts\Initable $component */
			$i = new $initable;

			if ( $i instanceof Initable ) {
				$i->init();
			} else {
				trigger_error( "$initable does not implement \PdkPluginBoilerplate\Framework\Contracts\Initable and has not been initialised." );
			}
		}
	}


}