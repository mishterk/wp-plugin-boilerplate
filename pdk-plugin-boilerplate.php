<?php
/**
 * Plugin Name:         PDK Plugin Boilerplate
 * Plugin URI:          http://philkurth.com.au
 * Description:         A boilerplate for plugin development
 * Version:             0.1
 * Author:              Phil Kurth
 * Author URI:          http://philkurth.com.au
 * GitHub Plugin URI:   https://github.com/mishterk/wp-plugin-boilerplate
 * Requires at least:   5.1
 * Tested up to:        5.1
 * License:             GPL2
 * License URI:         https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path:         /languages
 * Text Domain:         pdk-plugin-boilerplate
 */

// If this file is called directly, abort.
defined( 'WPINC' ) or die();

define( 'PDK_PLUGIN_BOILERPLATE_MIN_PHP_VERSION', 7.0 );
define( 'PDK_PLUGIN_BOILERPLATE_PLUGIN_NAME', 'PDK Plugin Boilerplate' );
define( 'PDK_PLUGIN_BOILERPLATE_PLUGIN_VERSION', 1.0 );
define( 'PDK_PLUGIN_BOILERPLATE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PDK_PLUGIN_BOILERPLATE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

if ( version_compare( PHP_VERSION, PDK_PLUGIN_BOILERPLATE_MIN_PHP_VERSION, '>=' ) ) {
	require_once PDK_PLUGIN_BOILERPLATE_PLUGIN_DIR . 'app/Plugin.php';
	$plugin = new \PdkPluginBoilerplate\Plugin();
	$plugin->init();

	$app = new \PdkPluginBoilerplate\Framework\Container\Application();
	$app->register_provider(new \PdkPluginBoilerplate\Providers\AjaxServiceProvider());

} else {
	require_once PDK_PLUGIN_BOILERPLATE_PLUGIN_DIR . 'framework/AdminNotices/AdminNotice.php';
	require_once PDK_PLUGIN_BOILERPLATE_PLUGIN_DIR . 'app/AdminNotices/FailedPhpVersionNotice.php';
	$notice = new \PdkPluginBoilerplate\AdminNotices\FailedPhpVersionNotice( PDK_PLUGIN_BOILERPLATE_PLUGIN_NAME, PDK_PLUGIN_BOILERPLATE_MIN_PHP_VERSION );
	$notice->init();
}