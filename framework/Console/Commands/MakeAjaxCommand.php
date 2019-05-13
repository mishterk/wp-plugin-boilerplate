<?php


namespace PdkPluginBoilerplate\Framework\Console\Commands;


use WP_CLI;


class MakeAjaxCommand {


	/**
	 * @throws \Exception
	 */
	public function init() {
		WP_CLI::add_command( 'pdkpluginbp make:ajax', static::class );
	}


	/**
	 * Creates an AJAX handler class
	 *
	 * <name>
	 * : The class name. e.g; MyAjaxAction
	 *
	 * @param $args
	 *
	 * @throws WP_CLI\ExitException
	 */
	public function __invoke( $args ) {
		$target_dir  = $this->get_target_dir();
		$class_name  = $args[0];
		$destination = "$target_dir/$class_name.php";

		// if already exists, bail
		if ( file_exists( $destination ) ) {
			WP_CLI::error( "$class_name class already exists." );
		}

		$stub = str_replace(
			[
				'namespace PdkPluginBoilerplate\Framework\Ajax',
				'DummyClass',
			],
			[
				'namespace PdkPluginBoilerplate\App\Ajax',
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
		return dirname( dirname( dirname( __DIR__ ) ) ) . '/app/Ajax';
	}


	// todo - need a better way to get the path
	private function get_stub() {
		return dirname( __DIR__ ) . '/stubs/ajax.stub';
	}


}