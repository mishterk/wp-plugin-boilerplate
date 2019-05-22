<?php


namespace PdkPluginBoilerplate\Tests\Mocks;


use PdkPluginBoilerplate\Framework\Container\Application;
use PdkPluginBoilerplate\Framework\Providers\ServiceProviderBase;


class ServiceProvider extends ServiceProviderBase {


	public $test_key;
	public $test_binding;


	/**
	 * Register this service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->bind( $this->test_key, $this->test_binding );
	}


}