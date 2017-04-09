<?php
/**
 * New eNevada Organization Administration Screen
 */

// no direct access
defined('ABSPATH') or die('No direct access');

// variables
$resource = new en_Resource();

// get the id
if(filter_has_var(INPUT_POST, 'id')){
	$resource->id = filter_has_var(INPUT_POST, 'id') ? filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT) : 0;
}else{
	$resource->id = filter_has_var(INPUT_GET, 'id') ? filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) : 0;
}

// save the data
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	// retrieve the input
	$resource->categories = filter_has_var(INPUT_POST, 'categories') ? $_POST['categories'] : array();
	$resource->description = filter_has_var(INPUT_POST, 'description') ? $_POST['description'] : '';
	$resource->name = filter_has_var(INPUT_POST, 'name') ? $_POST['name'] : '';
	$resource->org = filter_has_var(INPUT_POST, 'org') ? $_POST['org'] : 0;
	$resource->status = filter_has_var(INPUT_POST, 'status') ? $_POST['status'] : '';
	$resource->website = filter_has_var(INPUT_POST, 'website') ? $_POST['website'] : '';
	
	// validate
	if($resource->save()){
		if(filter_has_var(INPUT_POST, 'saveAndClose')){
			echo '<script type="text/javascript">window.location.href = "' . (is_ssl() ? 'https://' : 'http://') . $_SERVER[HTTP_HOST] . '/wp-admin/admin.php?page=enam-organizations";</script>';
		}
		$message = 'Resource saved!';
	}else{
		$notice = $resource->getLastError();
	}
}else{
	// load the resource information from the database
	$resource->load();
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
	<input type="hidden" name="id" value="<?php echo $resource->id; ?>">
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row"><label for="status" class="required">Status</label></th>
				<td>
					<select name="status" id="status">
						<option value="publish"<?php echo $resource->status == 'publish' ? ' selected="selected"' : ''; ?>>Published</option>
						<option value="draft"<?php echo $resource->status == 'draft' ? ' selected="selected"' : ''; ?>>Draft</option>
						<option value="trash"<?php echo $resource->status == 'trash' ? ' selected="selected"' : ''; ?>>Trash</option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="name" class="required">Resource Name</label></th>
				<td><input name="name" id="name" value="<?php echo $resource->name; ?>" class="regular-text" type="text"></td>
			</tr>
			<tr>
				<th scope="row"><label for="org" class="required">Organization</label></th>
				<td>
					<select name="org" id="org">
						<option value="0">-- Select An Organization --</option>
						<?php 
						$organizations = en_get_organizations($resource->org);
						foreach($organizations as $org): 
						?>
							<option value="<?php echo $org->id; ?>"<?php echo ($resource->org == $org->id ? ' selected' : ''); ?>><?php echo $org->name; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="description" class="required">Description</label></th>
				<td><textarea name="description" id="description" style="height:100px;width:350px;"><?php echo $resource->description; ?></textarea></td>
			</tr>
			<tr>
				<th scope="row"><label for="website">Website Address</label></th>
				<td><input name="website" id="website" value="<?php echo $resource->website; ?>" class="regular-text" type="text"></td>
			</tr>
			<tr>
				<th scope="row"><label class="required">Categories</label></th>
				<td>
					<?php 
					$categories = en_get_categories();
					foreach($categories as $cat):
					?>
						<label for="cat<?php echo $cat->id; ?>"><input type="checkbox" name="categories[]"  id="cat<?php echo $cat->id; ?>" value="<?php echo $cat->id; ?>"<?php echo (in_array($cat->id, $resource->categories) ? ' checked' : ''); ?>> <?php echo $cat->name; ?></label><br>
					<?php endforeach; ?>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<input name="save" id="save" class="button button-primary" value="Save" type="submit">
		<input name="saveAndClose" id="saveAndClose" class="button button-primary" value="Save &amp; Close" type="submit">
	</p>