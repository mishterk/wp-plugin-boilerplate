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

require_once PDK_PLUGIN_BOILERPLATE_PLUGIN_DIR . '/framework/AutoLoader.php';

$autoloader = new \PdkPluginBoilerplate\Framework\AutoLoader();
$autoloader->register();
$autoloader->addNamespace( 'PdkPluginBoilerplate', PDK_PLUGIN_BOILERPLATE_PLUGIN_DIR . 'app' );
$autoloader->addNamespace( 'PdkPluginBoilerplate\\Framework', PDK_PLUGIN_BOILERPLATE_PLUGIN_DIR . 'framework' );
$autoloader->addNamespace( 'PdkPluginBoilerplate\\Tests', PDK_PLUGIN_BOILERPLATE_PLUGIN_DIR . 'tests' );

if ( false === version_compare( PHP_VERSION, PDK_PLUGIN_BOILERPLATE_MIN_PHP_VERSION, '>=' ) ) {

	// Plugin won't load due to incompatible environmental conditions
	$notice = new \PdkPluginBoilerplate\AdminNotices\FailedPhpVersionNotice( PDK_PLUGIN_BOILERPLATE_PLUGIN_NAME, PDK_PLUGIN_BOILERPLATE_MIN_PHP_VERSION );
	$notice->init();

} else {

	// Load the plugin
	$plugin = new \PdkPluginBoilerplate\Framework\Container\Plugin( PDK_PLUGIN_BOILERPLATE_PLUGIN_DIR, PDK_PLUGIN_BOILERPLATE_PLUGIN_URL );
	$plugin->register_provider( new \PdkPluginBoilerplate\Providers\AjaxServiceProvider() );

	\PdkPluginBoilerplate\View\View::init();

	// todo - move the script loader
	add_action( 'wp_enqueue_scripts', function () {
		wp_enqueue_script( 'pdk-plugin-boilerplate', PDK_PLUGIN_BOILERPLATE_PLUGIN_URL . 'assets/build/js/app.js', [ 'jquery' ], PDK_PLUGIN_BOILERPLATE_PLUGIN_VERSION, true );
	} );

}