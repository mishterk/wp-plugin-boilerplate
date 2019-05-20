<?php


namespace PdkPluginBoilerplate\Framework\Providers;


use PdkPluginBoilerplate\Framework\Console\Commands\MakeAjaxCommand;
use PdkPluginBoilerplate\Framework\Console\Commands\MakeProviderCommand;
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
		$this->app = $app;

		if ( ! $this->is_running_wpcli() ) {
			return;
		}

		$this->app->singleton( 'command.ajax.make', MakeAjaxCommand::class );
		$this->app->singleton( 'command.provider.make', MakeProviderCommand::class );
	}


	public function plugins_loaded() {
		if ( ! $this->is_running_wpcli() ) {
			return;
		}

		$this->app->make( 'command.ajax.make' )->init();
		$this->app->make( 'command.provider.make' )->init();
	}


	protected function is_running_wpcli() {
		return defined( 'WP_CLI' ) and WP_CLI;
	}


}