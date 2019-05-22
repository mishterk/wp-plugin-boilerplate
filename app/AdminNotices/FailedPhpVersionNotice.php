<?php


namespace PdkPluginBoilerplate\AdminNotices;


use PdkPluginBoilerplate\Framework\AdminNotices\AdminErrorNotice;


class FailedPhpVersionNotice extends AdminErrorNotice {


	/**
	 * @param $plugin_name
	 * @param $min_php_version
	 */
	public function __construct( $plugin_name, $min_php_version ) {
		$this->message = "The <em>{$plugin_name}</em> plugin requires PHP version {$min_php_version} or higher. The plugin is technically active but is not currently functioning.";
	}


}