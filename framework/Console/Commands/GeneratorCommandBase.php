<?php


namespace PdkPluginBoilerplate\Framework\Console\Commands;


use PdkPluginBoilerplate\Framework\Container\Application;
use WP_CLI;


/**
 * Class GeneratorCommandBase
 * @package PdkPluginBoilerplate\Framework\Console\Commands
 *
 * Note: we can't use container-based DI in here as it fails on the command line. This is due to WP CLI's
 *  CommandFactory.php directly instantiating the registered command class and effectively bypassing our
 *  container. If/when container access is required in CLI commands, use static accessors.
 */
abstract class GeneratorCommandBase {


	protected function prevent_file_override( $file_path ) {
		if ( file_exists( $file_path ) ) {
			WP_CLI::error( "$file_path already exists." );
		}
	}


	protected function ensure_directory_path_exists( $directory ) {
		if ( ! file_exists( $directory ) and false === wp_mkdir_p( $directory ) ) {
			WP_CLI::error( "Failed to create dir $directory. Try creating the directory then running the command again." );
		}
	}


	protected function get_target_dir( $path ) {
		return Application::get_instance()->make( 'path.app' ) . $path;
	}


	protected function get_stub( $stub ) {
		return Application::get_instance()->make( 'path.framework' ) . '/Console/stubs/' . $stub;
	}


}