<?php
abstract class Abstract_Create_Users_From_Posts_User {
	
	/**
	 * @var int $user_id
	 */
	private $user_id;
	
	/**
	 * @var stdObject $post
	 */
	protected $post;
	
	/**
	 * @var mixed array $variables
	 */
	private $variables = array(
		'user_pass',
		'user_login',
		'user_nicename',
		'user_url',
		'user_email',
		'display_name',
		'nickname',
		'first_name',
		'last_name',
		'description',
		'rich_editing',
		'user_registered',
		'role',
		'jabber',
		'aim',
		'yim',
	);
	
	/**
	 * Constructor\
	 * 
	 * @param stdObject $post
	 */
	protected function __construct( $post ){
		$this->post = $post;
		
		$this->retrieve_information();
	}
	
	/**
	 * Saves the user to the database, if the user already exists it
	 * will be retrieved from the the database. When still no user is
	 * found, a WP_Error will be returned.
	 * 
	 * @return int|WP_Error
	 */
	function save_user(){
		$user_id = wp_insert_user( $this->variables );
		
		if( ! is_wp_error( $user_id ) ){ // No error
			$this->user_id = $user_id;
		} else { // Error, try to get existing user with conflicting login name
			$user = get_user_by('login', $this->variables['user_login'] );
			
			// If a user was found, get it's ID and store it.
			if( $user !== false )
				$user_id = $this->user_id = $user->ID;
		}
		
		return $user_id;
	}
	
	/**
	 * Assign post to this user. Only works when user is set.
	 */
	function assign_post_to_user(){
		if( empty( $this->user_id ) )
			return false;
		
		return wp_update_post( array(
			'ID' => $this->post->ID,
			'post_author' => $this->user_id
		) );
	}
	
	/**
	 * Check if user exists
	 * 
	 * @return boolean $exists
	 */
	function exists(){
		if( $user_id = username_exists( $this->variables['user_login'] ) ){
			$this->user_id = $user_id;
			return true;
		}
		return false;
	}
	
	/**
	 * Get user ID
	 * 
	 * @return int $user_id
	 */
	function get_user_id(){
		return $this->user_id;
	}
	
	/**
	 * Get post
	 * 
	 * @return stdObject
	 */
	function get_post(){
		return $this->post;
	}
	
	/**
	 * Get variable
	 * 
	 * @param string $name
	 * @return mixed|NULL
	 */
	function get_variable( $name ){
		if( isset( $this->variables[ $name ] ) )
			return $this->variables[ $name ];
		return null;
	}
	
	/**
	 * Get all variables
	 * 
	 * @return mixed
	 */
	function get_variables(){
		return $this->variables;
	}	
	
	/**
	 * Set variable
	 * 
	 * @param string $name
	 * @param mixed $value
	 */
	function set_variable( $name, $value ){
		$this->variables[ $name ] = $value;
	}
	
	/**
	 * Abstract function retrieveInformation
	 */
	abstract function retrieve_information();
}