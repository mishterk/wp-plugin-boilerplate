<?php


namespace PdkPluginBoilerplate\Framework\Providers;


use PdkPluginBoilerplate\Framework\Utils\Config;


class ConfigServiceProvider extends ServiceProviderBase {


	/**
	 * @var Config
	 */
	private $config;


	/**
	 * Register this service provider.
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function register() {
		$this->app->singleton( 'config', Config::class );
	}


	/**
	 * Load config from all configuration files in config directory
	 *
	 * @throws \Exception
	 */
	public function load_configuration_files() {
		$this->config = $this->app->make( 'config' );
		$config_dir   = $this->app->make( 'path.config' );

		if ( is_dir( $config_dir ) ) {
			$this->load_files_in_dir( $config_dir );
		}
	}


	private function load_files_in_dir( $dir, $context = '' ) {
		$resource = @opendir( $dir );

		if ( $resource === false ) {
			return;
		}

		while ( ( $file = readdir( $resource ) ) !== false ) {

			if ( in_array( $file, [ '.', '..' ] ) ) {
				continue;
			}

			$file_path = "$dir/$file";
			$key       = pathinfo( $file_path, PATHINFO_FILENAME );
			$ext       = pathinfo( $file_path, PATHINFO_EXTENSION );

			if ( ! ( is_dir( $file_path ) or $ext === 'php' ) ) {
				continue;
			}

			if ( $context ) {
				$key = "$context.$key";
			}

			if ( is_dir( $file_path ) ) {
				$this->load_files_in_dir( $file_path, $key );
				continue;
			}

			$this->config->set( $key, include $file_path );
		}

		closedir( $resource );
	}


}