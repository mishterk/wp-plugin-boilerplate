<?php


namespace PdkPluginBoilerplate\Framework\Providers;


use PdkPluginBoilerplate\Framework\Container\Application;


abstract class ServiceProviderBase {


	/**
	 * Register this service provider.
	 *
	 * @param Application $app
	 *
	 * @return void
	 */
	abstract public function register( Application $app );


}