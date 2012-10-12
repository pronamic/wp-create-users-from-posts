<?php
class Create_Users_From_Posts_Pronamic_Company_User extends Abstract_Create_Users_From_Posts_User {
	
	/**
	 * Constructor
	 * 
	 * @param stdObject $post
	 */
	function __construct( $post ){
		parent::__construct( $post );
	}
	
	/**
	 * Fill the parent object with as much information as possible
	 */
	function retrieve_information(){
		$variables = array(
			'user_pass' => wp_generate_password(),
			'user_login' => get_post_meta( $this->post->ID, '_pronamic_company_email', true ),
			'user_nicename' => $this->post->post_title,
			'user_url' => get_post_meta( $this->post->ID, '_pronamic_company_website', true ),
			'user_email' => get_post_meta( $this->post->ID, '_pronamic_company_email', true ),
			'display_name' => $this->post->post_title
		);
		
		foreach( $variables as $key => $value )
			$this->set_variable( $key, $value );
	}
}