<?php


namespace PdkPluginBoilerplate\Framework\Providers;


use PdkPluginBoilerplate\Framework\Console\Commands\MakeAjaxCommand;
use PdkPluginBoilerplate\Framework\Container\Application;


class WpCliServiceProvider extends ServiceProviderBase {


	/**
	 * @var Application
	 */
	protected $app;


	/**
	 * @param Application $app
	 */
	public function register( Application $app ) {
		if ( ! $this->is_running_wpcli() ) {
			return;
		}

		$this->app = $app;

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