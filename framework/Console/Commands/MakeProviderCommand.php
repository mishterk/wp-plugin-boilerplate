<?php


namespace PdkPluginBoilerplate\Framework\Console\Commands;


use WP_CLI;


class MakeProviderCommand extends GeneratorCommandBase {


	/**
	 * @throws \Exception
	 */
	public function init() {
		WP_CLI::add_command( 'pdkpluginbp make:provider', static::class );
	}


	/**
	 * Creates a service provider class
	 *
	 * <name>
	 * : The class name. e.g; MyProvider
	 *
	 * @param $args
	 *
	 * @throws WP_CLI\ExitException
	 */
	public function __invoke( $args ) {
		$target_dir  = $this->get_target_dir();
		$class_name  = $args[0];
		$destination = "$target_dir/$class_name.php";

		$this->ensure_directory_path_exists( $target_dir );
		$this->prevent_file_override( $destination );

		$stub = str_replace(
			[
				'namespace PdkPluginBoilerplate\Framework\Providers',
				'DummyClass',
			],
			[
				'namespace PdkPluginBoilerplate\App\Providers',
				$class_name,
			],
			file_get_contents( $this->get_stub() )
		);

		$created = ( false !== file_put_contents( $destination, $stub ) );

		if ( $created ) {
			WP_CLI::success( "$class_name class created." );
		} else {
			WP_CLI::error( "Failed to create $class_name class." );
		}
	}


	// todo - need a better way to get the path
	private function get_target_dir() {
		return dirname( dirname( dirname( __DIR__ ) ) ) . '/app/Providers';
	}


	// todo - need a better way to get the path
	private function get_stub() {
		return dirname( __DIR__ ) . '/stubs/provider.stub';
	}


}