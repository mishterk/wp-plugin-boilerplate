<?php


namespace PdkPluginBoilerplate\Framework\Providers;


use PdkPluginBoilerplate\Framework\Console\Commands\MakeAjaxCommand;
use PdkPluginBoilerplate\Framework\Container\Container;


class WpCliServiceProvider extends ServiceProviderBase {


	/**
	 * @var \PdkPluginBoilerplate\Framework\Container\Container
	 */
	protected $app;


	/**
	 * @param Container $container
	 */
	public function register( Container $container ) {
		if ( ! $this->is_running_wpcli() ) {
			return;
		}

		$this->app = $container;

		$this->registerMakeAjaxCommand();
	}


	protected function is_running_wpcli() {
		return defined( 'WP_CLI' ) and WP_CLI;
	}


	protected function registerMakeAjaxCommand() {
		$this->app->singleton( 'command.ajax.make', function ( $app ) {

			$command = new MakeAjaxCommand();
			$command->init();

			return $command;
		} );

		$this->app->make( 'command.ajax.make' );
	}


}