<?php


namespace PdkPluginBoilerplate\Framework\Foundation;


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