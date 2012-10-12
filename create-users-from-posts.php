<?php
/*
 Plugin Name: Create Users From Posts
 Plugin URI: http://pronamic.eu/wordpress/create-users-from-posts/
 Description: Gets the properties of a post and converts it to the properties of a user
 Version: 0.1
 Requires at least: 3.0
 Author: Pronamic
 Author URI: http://pronamic.eu/
 License: GPLv2
*/

/**
 * Main class that bootstraps the application
 * 
 * @version 22-08-12
 */
class Create_Users_From_Posts {
	
	/**
	 * Bootstraps the plugin
	 */
	static function bootstrap(){
		// Translate
		add_action('init', array( __CLASS__, 'localize') );
		
		add_action('admin_menu', array( __CLASS__, 'admin_menu') );
	}
	
	/**
	 * Called on admin_menu hook. Adds admin pages.
	 */
	static function admin_menu(){
		add_submenu_page(
			'tools.php',
			__('Create Users from Posts', 'create-users-from-posts-plugin'),
			__('Users from Posts', 'create-users-from-posts-plugin'),
			'manage_options',
			'create-users-from-posts',
			array( __CLASS__, 'admin_page')
		);
	}
	
	/**
	 * Builds the post to author converter page
	 */
	static function admin_page(){
		// Roles
		global $wp_roles;
		$roles = $wp_roles->roles;
		
		// Post types
		$post_types = get_post_types();
		
		// After submit
		if( isset( $_POST['submit'] ) && $_POST['submit'] != -1 &&
			isset( $_POST['post-type'] ) && $_POST['post-type'] != -1 &&
			isset( $_POST['author'] ) && ! empty( $_POST['author'] ) &&
			isset( $_POST['role'] ) && $_POST['role'] != -1 ){
			
			// Include classes
			include_once('classes/class-abstract-create-users-from-posts-user.php');
			include_once('classes/class-create-users-from-posts-pronamic-company-user.php');
			
			// Store generated users in results
			$results = array();
			
			// Query
			$query = new WP_Query( array(
				'post_type' => $_POST['post-type'],
				'posts_per_page' => -1
			) );
			while( $query->have_posts() ) {
				$query->the_post();
				global $post;

				// Build user
				$user = new Create_Users_From_Posts_Pronamic_Company_User( $post );
				$user->set_variable('role', $_POST['role'] );
				
				// Save user and assign it to a post
				$save_result = $user->save_user();
				$assign_result = $user->assign_post_to_user();
				
				if( ! is_wp_error( $save_result )  && $assign_result !== false )
					$results[] = array(
						'post_id' => $post->ID,
						'display_name' => $user->get_variable('display_name'),
						'user_id' => $user->get_user_id(),
						'user_login' => $user->get_variable('user_login'),
						'user_email' => $user->get_variable('user_email'),
						'user_pass' => $user->get_variable('user_pass')
					);
			}
		}
		
		include_once('admin-page.php');
	}
	
	/**
	 * Translates the plugin
	 */
	static function localize(){
		load_plugin_textdomain(
			'create-users-from-posts-plugin',
			false,
			dirname(plugin_basename(__FILE__)) . '/languages/'
		);
	}
}

/*
 * Bootsrap application
 */
Create_Users_From_Posts::bootstrap();