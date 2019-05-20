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
	 * @throws \Exception
	 */
	public function register( Application $app ) {
		$this->app = $app;
		$app->singleton( 'config', Config::class );
	}


	/**
	 * Load config from all configuration files in config directory
	 *
	 * @throws \Exception
	 */
	public function load_configuration_files() {
		/** @var Config $config */
		$config     = $this->app->make( 'config' );
		$config_dir = $this->app->make( 'path.config' );

		$resource = @opendir( $config_dir );

		if ( $resource === false ) {
			return;
		}

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