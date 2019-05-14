<?php


namespace PdkPluginBoilerplate\Tests\Mocks;


use PdkPluginBoilerplate\Framework\Container\Application;
use PdkPluginBoilerplate\Framework\Providers\ServiceProviderBase;


class ServiceProvider extends ServiceProviderBase {


	public $test_key;
	public $test_binding;


	/**
	 * @param $test_key
	 * @param $test_binding
	 */
	public function __construct( $test_key, $test_binding ) {
		$this->test_key     = $test_key;
		$this->test_binding = $test_binding;
	}


	/**
	 * Register this service provider.
	 *
	 * @param Application $app
	 *
	 * @return void
	 */
	public function register( Application $app ) {
		$app->bind( $this->test_key, $this->test_binding );
	}


}