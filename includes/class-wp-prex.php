<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://themeisle.com
 * @since      1.0.0
 *
 * @package    WP_Prex
 * @subpackage WP_Prex/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WP_Prex
 * @subpackage WP_Prex/includes
 * @author     Marius Cristea <marius@themeisle.com>
 */
class WP_Prex {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WP_Prex_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;
	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->loader = new WP_Prex_Loader();
		$this->load_hooks();

	}

	public function load_hooks() {
		$this->loader->add_filter( 'wp_privacy_personal_data_erasers', $this, 'register_extended_erasers', 10 );
		$this->loader->add_filter( 'gform_ip_address', $this, 'block_ip_record', 10 );
	}

	/**
	 * Block ip record.
	 *
	 * @return string Empty string.
	 */
	function block_ip_record() {
		return __return_empty_string();
	}

	/**
	 * Register eraser.
	 *
	 * @param array $erasers Previous erasers.
	 *
	 * @return mixed Erasers list.
	 */
	public function register_extended_erasers( $erasers ) {
		$erasers['wprex-user-delete'] = array(
			'eraser_friendly_name' => __( 'ThemeIsle: Delete User record.' ),
			'callback'             => array( $this, 'erase_user' ),
		);

		return $erasers;
	}

	/**
	 * Erase user data.
	 *
	 * @param string $email_address Email address.
	 * @param int    $page Current page.
	 *
	 * @return array
	 */
	public function erase_user( $email_address, $page = 1 ) {

		$page = (int) $page;

		$done    = true;
		$user_id = email_exists( $email_address );
		wp_delete_user( $user_id );

		return array(
			'items_removed'  => 1,
			'items_retained' => false, // always false in this example
			'messages'       => array(), // no messages in this example
			'done'           => $done,
		);
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    WP_Prex_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

}
