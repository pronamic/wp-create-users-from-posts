<h3><?php _e('Create users from posts', 'wp-create-users-from-posts-plugin'); ?></h3>

<form method="post" action="tools.php?page=post-to-author">
	<table>
		<tr>
			<td><?php _e('Select Post Type', 'wp-create-users-from-posts-plugin'); ?></td>
			<td>
				<select name="post-type">
					<option value="-1"> - <?php _e('None Selected', 'wp-create-users-from-posts-plugin'); ?> - </option>
					<?php foreach( $post_types as $key => $post_type ): ?>
						<option value="<?php echo $key; ?>"><?php echo $post_type; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
			<td><i><?php _e('Select the post type which you want to create users from.', 'wp-create-users-from-posts-plugin'); ?></i></td>
		</tr>
		<tr>
			<td><?php _e('Username', 'wp-create-users-from-posts-plugin'); ?></td>
			<td><input type="text" name="author" /></td>
			<td><i><?php _e('Login username of the user whom\'s posts you\'d like to convert.', 'wp-create-users-from-posts-plugin'); ?></i></td>
		</tr>
		<tr>
			<td><?php _e('Select Role', 'wp-create-users-from-posts-plugin'); ?></td>
			<td>
				<select name="role">
					<option value="-1"> - <?php _e('None Selected', 'wp-create-users-from-posts-plugin'); ?> - </option>
					<?php foreach( $roles as $key => $role ): ?>
						<option value="<?php echo $key; ?>"><?php echo $role['name']; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
			<td><i><?php _e('The role the newly created users will receive.', 'wp-create-users-from-posts-plugin'); ?></i></td>
		</tr>
	</table>
	<?php submit_button( __('Convert Posts', 'wp-create-users-from-posts-plugin') ); ?>
</form>

<?php if( isset( $_POST['submit'] ) ): ?>
	<style type="text/css">
		.wp-create-users-from-posts-results td {
			padding: 0 20px;
		}
	</style>
	<table class="wp-create-users-from-posts-results">
		<tr>
			<th><?php _e('Post ID', 'wp-create-users-from-posts-plugin'); ?></th>
			<th><?php _e('Name', 'wp-create-users-from-posts-plugin'); ?></th>
			<th><?php _e('User ID', 'wp-create-users-from-posts-plugin'); ?></th>
			<th><?php _e('Username', 'wp-create-users-from-posts-plugin'); ?></th>
			<th><?php _e('Email', 'wp-create-users-from-posts-plugin'); ?></th>
			<th><?php _e('Password', 'wp-create-users-from-posts-plugin'); ?></th>
		</tr>
		<?php foreach( $results as $result ): ?>
			<tr>
				<td><?php echo $result['post_id']; ?></td>
				<td><?php echo $result['display_name']; ?></td>
				<td><?php echo $result['user_id']; ?></td>
				<td><?php echo $result['user_login']; ?></td>
				<td><?php echo $result['user_email']; ?></td>
				<td><?php echo $result['user_pass']; ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php endif; ?>