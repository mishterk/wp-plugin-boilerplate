<?php


namespace PdkPluginBoilerplate\Framework\Providers;


use PdkPluginBoilerplate\Framework\Container\Application;
use PdkPluginBoilerplate\Framework\Utils\Config;


class ConfigServiceProvider extends ServiceProviderBase {


	/**
	 * @var Application
	 */
	protected $app;


	/**
	 * Register this service provider.
	 *
	 * @param Application $app
	 *
	 * @return void
	 */
	public function register( Application $app ) {
		$this->app = $app;
		$app->singleton( 'config', Config::class );
	}


	public function plugins_loaded() {
		/** @var Config $config */
		$config     = $this->app->make( 'config' );
		$config_dir = trailingslashit( $this->app->make( 'base_dir' ) ) . 'config';

		$resource = opendir( $config_dir );

		while ( ( $file = readdir( $resource ) ) !== false ) {
			$file_path = "$config_dir/$file";

			if ( is_dir( $file_path ) ) {
				continue;
			}

			$key = pathinfo( $file_path, PATHINFO_FILENAME );

			$config->set( $key, include $file_path );
		}

		closedir( $resource );
	}


}