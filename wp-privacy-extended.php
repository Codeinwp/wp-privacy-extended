<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://themeisle.com
 * @since             1.0.0
 * @package           wp_prex
 *
 * @wordpress-plugin
 * Plugin Name:       ThemeIsle - WordPress Privacy extension.
 * Version:           1.0.0
 * Author:            ThemeIsle
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * WordPress Available:  no
 * Requires License:    no
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-edd-fastspring-activator.php
 */
function activate_wp_prex() {
	WP_Prex_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-edd-fastspring-deactivator.php
 */
function deactivate_wp_prex() {
	WP_Prex_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_prex' );
register_deactivation_hook( __FILE__, 'deactivate_wp_prex' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_prex() {
	define( 'WP_PREX_BASEFILE', __FILE__ );
	define( 'WP_PREX_URL', plugin_dir_url( __FILE__ ) );
	define( 'WP_PREX_DIR', plugin_dir_path( __FILE__ ) );
	$plugin = new WP_Prex();
	$plugin->run();

}

spl_autoload_register( 'wp_prex_autoload' );
run_wp_prex();
/**
 * Load the class files.
 *
 * @param string $class Class Name.
 *
 * @return bool Either was loaded or not.
 */
function wp_prex_autoload( $class ) {

	$namespaces = array( 'WP_Prex' );
	foreach ( $namespaces as $namespace ) {
		if ( substr( $class, 0, strlen( $namespace ) ) == $namespace ) {
			$class_file = 'class-' . str_replace( '_', '-', strtolower( $class ) );
			$filename = WP_PREX_DIR . 'includes/' . $class_file . '.php';
			if ( is_readable( $filename ) ) {
				require_once $filename;

				return true;
			}

		}
	}

	return false;
}
