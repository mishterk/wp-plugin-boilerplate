<?php


namespace PdkPluginBoilerplate\Framework\Foundation\Providers;


use PdkPluginBoilerplate\Framework\Foundation\Console\MakeAjaxCommand;
use PdkPluginBoilerplate\Framework\Foundation\Container;
use PdkPluginBoilerplate\Framework\Foundation\ServiceProviderBase;


class WpCliServiceProvider extends ServiceProviderBase {


	/**
	 * @var Container
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