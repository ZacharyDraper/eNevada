<?php
// this displays the organizations screen

// no direct access
defined('ABSPATH') or die('No direct access');

// load the helper file
require_once('helper.php');

// variables
$id = 0;
$message = '';
$notice = '';

// check if editing or trashing
$editing = false;
if(filter_has_var(INPUT_GET, 'action')){
	$id = filter_has_var(INPUT_GET, 'id') ? filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) : 0;
	if($_GET['action'] == 'edit'){
		$editing = true;
	}elseif($_GET['action'] == 'trash'){
		$resource = new en_Resource();
		$resource->id = $id;
		if($resource->trash()){
			$message = 'Resource trashed.';
		}else{
			$notice = 'Unable to trash.';
		}
	}
} 

// determine which heading to show
if($editing){
	if($id > 0){
		$heading = 'Edit Resource';
	}else{
		$heading = 'New Resource';
	}
}else{
	$heading = 'Resources <a href="' . (is_ssl() ? 'https://' : 'http://') . $_SERVER[HTTP_HOST] . $_SERVER[REQUEST_URI] . '&amp;action=edit&amp;id=0" class="add-new-h2">Add New</a>';
}
?>
<div class="wrap">
	<h2><span class="dashicons dashicons-book-alt"></span> <?php echo $heading; ?></h2>
	<?php 
	if($editing){
		require_once('resource-form.php');
	}else{
		require_once('resource-list.php');
	}
	?>
</div>