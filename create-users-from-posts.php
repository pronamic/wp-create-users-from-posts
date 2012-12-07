<?php
/*
Plugin Name: Create Users From Posts
Plugin URI: http://pronamic.eu/wordpress/create-users-from-posts/
Description: Gets the properties of a post and converts it to the properties of a user

Version: 0.1
Requires at least: 3.0

Author: Pronamic
Author URI: http://pronamic.eu/

Text Domain: create_users_from_posts
Domain Path: /languages/

License: GPL

GitHub URI: https://github.com/pronamic/wp-create-users-from-posts
*/

class Pronamic_CreateUsersFromPosts_Plugin {
	/**
	 * Bootstraps the plugin
	 */
	public static function bootstrap() {
		add_action( 'init',       array( __CLASS__, 'localize' ) );
		
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
	}
	
	/**
	 * Called on admin_menu hook. Adds admin pages.
	 */
	public static function admin_menu() {
		add_submenu_page(
			'tools.php',
			__( 'Create Users from Posts', 'create_users_from_posts' ),
			__( 'Users from Posts', 'create_users_from_posts' ),
			'manage_options',
			'create-users-from-posts',
			array( __CLASS__, 'admin_page' )
		);
	}
	
	/**
	 * Builds the post to author converter page
	 */
	public static function admin_page() {
		include 'admin/admin.php';
	}
	
	/**
	 * Translates the plugin
	 */
	public static function localize() {
		load_plugin_textdomain(
			'create_users_from_posts',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages/'
		);
	}
}

/*
 * Bootsrap
 */
Pronamic_CreateUsersFromPosts_Plugin::bootstrap();
