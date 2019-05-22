<?php


namespace PdkPluginBoilerplate\Framework\Providers;


use PdkPluginBoilerplate\Framework\Container\Application;


abstract class ServiceProviderBase {


	/**
	 * @var Application
	 */
	protected $app;


	/**
	 * @param Application $app
	 */
	public function __construct( Application $app ) {
		$this->app = $app;
	}


	/**
	 * Register this service provider.
	 *
	 * @return void
	 */
	abstract public function register();


}