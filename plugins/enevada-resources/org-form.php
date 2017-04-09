<?php
/**
 * New eNevada Organization Administration Screen
 */

// no direct access
defined('ABSPATH') or die('No direct access');

// variables
$organization = new en_Organization();

// get the id
if(filter_has_var(INPUT_POST, 'id')){
	$organization->id = filter_has_var(INPUT_POST, 'id') ? filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT) : 0;
}else{
	$organization->id = filter_has_var(INPUT_GET, 'id') ? filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) : 0;
}

// save the data
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	// retrieve the input
	$organization->description = filter_has_var(INPUT_POST, 'description') ? $_POST['description'] : '';
	$organization->email = filter_has_var(INPUT_POST, 'email') ? $_POST['email'] : '';
	$organization->fname = filter_has_var(INPUT_POST, 'fname') ? $_POST['fname'] : '';
	$organization->lname = filter_has_var(INPUT_POST, 'lname') ? $_POST['lname'] : '';
	$organization->name = filter_has_var(INPUT_POST, 'name') ? $_POST['name'] : '';
	$organization->status = filter_has_var(INPUT_POST, 'status') ? $_POST['status'] : '';
	
	// validate
	if($organization->save()){
		if(filter_has_var(INPUT_POST, 'saveAndClose')){
			echo '<script type="text/javascript">window.location.href = "' . (is_ssl() ? 'https://' : 'http://') . $_SERVER[HTTP_HOST] . '/wp-admin/admin.php?page=enrm-orgs";</script>';
		}
		$message = 'Organization saved!';
	}else{
		$notice = $organization->getLastError();
	}
}else{
	// load the organization information from the database
	$organization->load();
}
?>
<style type="text/css">
	.required:after{
		color: red;
		content: '*';
	}
</style>
<?php if($notice): ?>
<div id="notice" class="notice notice-warning"><p id="has-newer-autosave"><?php echo $notice ?></p></div>
<?php endif; ?>
<?php if($message): ?>
<div id="message" class="updated"><p><?php echo $message; ?></p></div>
<?php endif; ?>
<div id="lost-connection-notice" class="error hidden">
	<p><span class="spinner"></span> <?php _e( '<strong>Connection lost.</strong> Saving has been disabled until you&#8217;re reconnected.' ); ?>
	<span class="hide-if-no-sessionstorage"><?php _e( 'We&#8217;re backing up this post in your browser, just in case.' ); ?></span>
	</p>
</div>
<form name="post" action="" method="post" id="post">
	<input type="hidden" name="id" value="<?php echo $organization->id; ?>">
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row"><label for="status" class="required">Status</label></th>
				<td>
					<select name="status" id="status">
						<option value="publish"<?php echo $organization->status == 'publish' ? ' selected="selected"' : ''; ?>>Published</option>
						<option value="draft"<?php echo $organization->status == 'draft' ? ' selected="selected"' : ''; ?>>Draft</option>
						<option value="trash"<?php echo $organization->status == 'trash' ? ' selected="selected"' : ''; ?>>Trash</option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="name" class="required">Organization Name</label></th>
				<td><input name="name" id="name" value="<?php echo $organization->name; ?>" class="regular-text" type="text"></td>
			</tr>
			<tr>
				<th scope="row"><label for="description" class="required">Description</label></th>
				<td><textarea name="description" id="description" style="height:100px;width:350px;"><?php echo $organization->description; ?></textarea></td>
			</tr>
			<tr>
				<th scope="row"><label for="fname" class="required">First Name</label></th>
				<td><input name="fname" id="fname" value="<?php echo $organization->fname; ?>" class="regular-text" type="text"></td>
			</tr>
			<tr>
				<th scope="row"><label for="lname" class="required">Last Name</label></th>
				<td><input name="lname" id="lname" value="<?php echo $organization->lname; ?>" class="regular-text" type="text"></td>
			</tr>
			<tr>
				<th scope="row"><label for="email" class="required">Email Address</label></th>
				<td><input name="email" id="email" value="<?php echo $organization->email; ?>" class="regular-text" type="text"></td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<input name="save" id="save" class="button button-primary" value="Save" type="submit">
		<input name="saveAndClose" id="saveAndClose" class="button button-primary" value="Save &amp; Close" type="submit">
	</p>