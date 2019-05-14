<?php


namespace PdkPluginBoilerplate\Framework\Console\Commands;


use WP_CLI;


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


}