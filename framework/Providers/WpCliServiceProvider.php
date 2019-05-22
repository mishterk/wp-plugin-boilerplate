<?php


namespace PdkPluginBoilerplate\Framework\Providers;


use PdkPluginBoilerplate\Framework\Console\Commands\MakeAjaxCommand;
use PdkPluginBoilerplate\Framework\Console\Commands\MakeProviderCommand;


class WpCliServiceProvider extends ServiceProviderBase {


	public function register() {
		if ( ! $this->is_running_wpcli() ) {
			return;
		}

		$this->app->singleton( MakeAjaxCommand::class );
		$this->app->singleton( MakeProviderCommand::class );
	}


	public function boot() {
		if ( ! $this->is_running_wpcli() ) {
			return;
		}

		$this->app->make( MakeAjaxCommand::class )->init();
		$this->app->make( MakeProviderCommand::class )->init();
	}


	protected function is_running_wpcli() {
		return defined( 'WP_CLI' ) and WP_CLI;
	}


}