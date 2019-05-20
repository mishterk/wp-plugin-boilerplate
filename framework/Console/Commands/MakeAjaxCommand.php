<?php


namespace PdkPluginBoilerplate\Framework\Console\Commands;


use WP_CLI;


class MakeAjaxCommand extends GeneratorCommandBase {


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
		$target_dir  = $this->get_target_dir( '/Ajax' );
		$class_name  = $args[0];
		$destination = "$target_dir/$class_name.php";

		$this->ensure_directory_path_exists( $target_dir );
		$this->prevent_file_override( $destination );

		$stub = str_replace(
			[
				'namespace PdkPluginBoilerplate\Framework\Ajax',
				'DummyClass',
			],
			[
				'namespace PdkPluginBoilerplate\App\Ajax',
				$class_name,
			],
			file_get_contents( $this->get_stub( 'ajax.stub' ) )
		);

		$created = ( false !== file_put_contents( $destination, $stub ) );

		if ( $created ) {
			WP_CLI::success( "$class_name class created." );
		} else {
			WP_CLI::error( "Failed to create $class_name class." );
		}
	}


}