<?php


namespace PdkPluginBoilerplate\Framework\Providers;


use PdkPluginBoilerplate\Framework\Container\Container;


abstract class ServiceProviderBase {


	/**
	 * Register this service provider.
	 *
	 * @param Container $container
	 *
	 * @return void
	 */
	abstract public function register( Container $container );


}