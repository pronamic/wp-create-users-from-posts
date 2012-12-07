<?php 

global $wp_roles, $post;

?>
<div class="wrap">
	<?php screen_icon( ); ?>

	<h2>
		<?php _e( 'Create users from posts', 'create_users_from_posts' ); ?>
	</h2>
	
	<form method="post" action="">
		<?php wp_nonce_field( 'generate_users', 'create_users_from_posts_nonce' ); ?>

		<?php 

		$post_type         = filter_input( INPUT_POST, 'cufp_post_type', FILTER_SANITIZE_STRING );
		$author_name       = filter_input( INPUT_POST, 'cufp_author_name', FILTER_SANITIZE_STRING );
		$role              = filter_input( INPUT_POST, 'cufp_role', FILTER_SANITIZE_STRING );
		$test              = filter_input( INPUT_POST, 'cufp_test', FILTER_VALIDATE_BOOLEAN );

		$meta_key_login    = filter_input( INPUT_POST, 'cufp_meta_key_login', FILTER_SANITIZE_STRING );
		$meta_key_email    = filter_input( INPUT_POST, 'cufp_meta_key_email', FILTER_SANITIZE_STRING );
		$meta_key_url      = filter_input( INPUT_POST, 'cufp_meta_key_url', FILTER_SANITIZE_STRING );
		$meta_key_password = filter_input( INPUT_POST, 'cufp_meta_key_password', FILTER_SANITIZE_STRING );
		
		?>
		<table class="form-table">
			<tr>
				<th scope="row">
					<?php _e( 'Select Post Type', 'create_users_from_posts' ); ?>
				</th>
				<td>
					<select name="cufp_post_type">
						<option value=""> - <?php _e( 'None Selected', 'create_users_from_posts' ); ?> - </option>

						<?php 
						
						$post_types = get_post_types( array(), 'objects' );
						
						foreach ( $post_types as $key => $p ): ?>
							<option value="<?php echo $key; ?>" <?php selected( $key, $post_type ); ?>><?php echo $p->label; ?></option>
						<?php endforeach; ?>
					</select>
					
					<p class="description">
						<?php _e( 'Select the post type which you want to create users from.', 'create_users_from_posts' ); ?>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e( 'Username', 'create_users_from_posts' ); ?>
				</th>
				<td>
					<input type="text" name="cufp_author_name" value="<?php echo esc_attr( $author_name ); ?>" />
					
					<p class="description">
						<?php _e( 'Login username of the user whom\'s posts you\'d like to convert.', 'create_users_from_posts' ); ?>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e( 'Select Role', 'create_users_from_posts' ); ?>
				</th>
				<td>
					<select name="cufp_role">
						<option value="-1"> - <?php _e( 'None Selected', 'create_users_from_posts' ); ?> - </option>

						<?php foreach ( $wp_roles->roles as $key => $r ) : ?>
							<option value="<?php echo $key; ?>" <?php selected( $key, $role ); ?>><?php echo $r['name']; ?></option>
						<?php endforeach; ?>
					</select>

					<p class="description">
						<?php _e( 'The role the newly created users will receive.', 'create_users_from_posts' ); ?>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e( 'Meta Key Login', 'create_users_from_posts' ); ?>
				</th>
				<td>
					<input type="text" name="cufp_meta_key_login" value="<?php echo esc_attr( $meta_key_login ); ?>" />

					<p class="description">
						<?php _e( 'For example: <code>_pronamic_company_email</code>.', 'create_users_from_posts' ); ?>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e( 'Meta Key E-Mail', 'create_users_from_posts' ); ?>
				</th>
				<td>
					<input type="text" name="cufp_meta_key_email" value="<?php echo esc_attr( $meta_key_email ); ?>" />

					<p class="description">
						<?php _e( 'For example: <code>_pronamic_company_email</code>.', 'create_users_from_posts' ); ?>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e( 'Meta Key URL', 'create_users_from_posts' ); ?>
				</th>
				<td>
					<input type="text" name="cufp_meta_key_url" value="<?php echo esc_attr( $meta_key_url ); ?>" />

					<p class="description">
						<?php _e( 'For example: <code>_pronamic_company_website</code>.', 'create_users_from_posts' ); ?>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e( 'Meta Key Password', 'create_users_from_posts' ); ?>
				</th>
				<td>
					<input type="text" name="cufp_meta_key_password" value="<?php echo esc_attr( $meta_key_password ); ?>" />

					<p class="description">
						<?php _e( 'Retrieve the password from on post meta key field, leave empty to generate an new password.', 'create_users_from_posts' ); ?>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php _e( 'Test', 'create_users_from_posts' ); ?>
				</th>
				<td>					
					<label for="cufp_test">
						<input id="cufp_test" name="cufp_test" <?php checked( $test ); ?> value="1" type="checkbox" />
						<?php _e( 'Run an test, no users will be created.', 'create_users_from_posts' ); ?>
					</label>
				</td>
			</tr>
		</table>

		<?php submit_button( __( 'Generate Users', 'create_users_from_posts' ), 'primary', 'generate_users' ); ?>
	</form>
	
	<?php if ( isset( $_POST['generate_users'] ) && wp_verify_nonce( $_POST['create_users_from_posts_nonce'], 'generate_users' ) ) : ?>

		<?php

		$error = false;

		if ( empty( $meta_key_login ) || empty( $meta_key_email ) ) {
			$error = true;
		}

		if ( $error ) : ?>
		
			<p>
				<?php _e( 'Something went wrong.', 'create_users_from_posts' ); ?>
			</p>

		<?php else : ?>

			<?php
			
			set_time_limit( 0 ); // If set to zero, no time limit is imposed. 

			$query = new WP_Query( array(
				'post_type'   => $post_type,
				'nopaging'    => true,
				'author_name' => $author_name
			) );
	
			if ( $query->have_posts() ) : ?>
		
				<table class="wp-list-table widefat fixed posts" cellspacing="0">
					<thead>
						<tr>
							<th><?php _e( 'Post ID', 'create_users_from_posts' ); ?></th>
							<th><?php _e( 'Name', 'create_users_from_posts' ); ?></th>
							<th><?php _e( 'Username', 'create_users_from_posts' ); ?></th>
							<th><?php _e( 'Email', 'create_users_from_posts' ); ?></th>
							<th><?php _e( 'Password', 'create_users_from_posts' ); ?></th>
							<th><?php _e( 'URL', 'create_users_from_posts' ); ?></th>
							<th><?php _e( 'Result', 'create_users_from_posts' ); ?></th>
							<th><?php _e( 'Error', 'create_users_from_posts' ); ?></th>
						</tr>
					</thead>
		
					<tbody>
			
						<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			
							<tr>
								<td>
									<?php the_ID(); ?>
								</td>
								<td>
									<?php the_title(); ?>
								</td>
								<td>
									<?php 
									
									$login = null;
									
									$login = get_post_meta( get_the_ID(), $meta_key_login, true );
									
									echo $login;
									
									?>
								</td>
								<td>
									<?php 
									
									$email = null;
									
									$email = get_post_meta( get_the_ID(), $meta_key_email, true );
									
									echo $email;
									
									?>
								</td>
								<td>
									<?php 
									
									$password = null;

									if ( ! empty( $meta_key_password ) ) {
										$password = get_post_meta( get_the_ID(), $meta_key_password, true );
									}

									if ( empty( $password ) ) {
										$password = wp_generate_password();
									}
									
									echo $password;
									
									?>
								</td>
								<td>
									<?php 

									$url = null;

									if ( ! empty( $meta_key_url ) ) {
										$url = get_post_meta( get_the_ID(), $meta_key_url, true );
									}
									
									echo $url;
									
									?>
								</td>
								<td>
									<?php 
									
									$userdata = array(
										'user_login'    => $login,
										'user_pass'     => $password,
										'user_nicename' => get_the_title(),
										'user_url'      => $url,
										'user_email'    => $email,
										'display_name'  => get_the_title(),
										'role'          => $role,
									);

									$result = false;

									if ( $test ) {
										
									} else {
										$result = wp_insert_user( $userdata );
	
										$user_id = null;
	
										if ( is_wp_error( $result ) ) {
											foreach ( array( 'login' => $login, 'email' => $email ) as $field => $value ) {
												$user = get_user_by( $field, $value );
												
												if ( $user !== false ) {
													$user_id = $user->ID;
												}
											}
										} else {
											$user_id = $result;
										}
										
										if ( !empty( $user_id ) ) {
											wp_update_post( array(
												'ID'          => get_the_ID(),
												'post_author' => $user_id
											) );
										}
										
										echo $user_id;
									}
										
									?>
								</td>
								<td>
									<?php 
									
									if ( is_wp_error( $result ) ) {
										echo $result->get_error_message();
									}
									
									?>
								</td>
							</tr>
			
						<?php endwhile; ?>
		
					</tbody>
				</table>
	
			<?php endif; ?>

		<?php endif; ?>

	<?php endif; ?>
</div>