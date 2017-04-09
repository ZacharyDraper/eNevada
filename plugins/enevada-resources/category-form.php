<?php
/**
 * New eNevada Organization Administration Screen
 */

// no direct access
defined('ABSPATH') or die('No direct access');

// variables
$category = new en_Category();

// get the id
if(filter_has_var(INPUT_POST, 'id')){
	$category->id = filter_has_var(INPUT_POST, 'id') ? filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT) : 0;
}else{
	$category->id = filter_has_var(INPUT_GET, 'id') ? filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) : 0;
}

// save the data
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	// retrieve the input
	$category->description = filter_has_var(INPUT_POST, 'description') ? $_POST['description'] : '';
	$category->name = filter_has_var(INPUT_POST, 'name') ? $_POST['name'] : '';
	$category->status = filter_has_var(INPUT_POST, 'status') ? $_POST['status'] : '';
	
	// validate
	if($category->save()){
		if(filter_has_var(INPUT_POST, 'saveAndClose')){
			echo '<script type="text/javascript">window.location.href = "/wp-admin/admin.php?page=enrm-categories";</script>';
		}
		$message = 'Category saved!';
	}else{
		$notice = $category->getLastError();
	}
}else{
	// load the organization information from the database
	$category->load();
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
	<input type="hidden" name="id" value="<?php echo $category->id; ?>">
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row"><label for="status" class="required">Status</label></th>
				<td>
					<select name="status" id="status">
						<option value="publish"<?php echo $category->status == 'publish' ? ' selected="selected"' : ''; ?>>Published</option>
						<option value="draft"<?php echo $category->status == 'draft' ? ' selected="selected"' : ''; ?>>Draft</option>
						<option value="trash"<?php echo $category->status == 'trash' ? ' selected="selected"' : ''; ?>>Trash</option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="name" class="required">Category Name</label></th>
				<td><input name="name" id="name" value="<?php echo $category->name; ?>" class="regular-text" type="text"></td>
			</tr>
			<tr>
				<th scope="row"><label for="description" class="required">Description</label></th>
				<td><input name="description" id="description" value="<?php echo $category->description; ?>" class="regular-text" type="text"></td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<input name="save" id="save" class="button button-primary" value="Save" type="submit">
		<input name="saveAndClose" id="saveAndClose" class="button button-primary" value="Save &amp; Close" type="submit">
	</p>