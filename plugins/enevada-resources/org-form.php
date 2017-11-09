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
	$organization->logo = filter_has_var(INPUT_POST, 'logo') ? $_POST['logo'] : '';
	$organization->name = filter_has_var(INPUT_POST, 'name') ? $_POST['name'] : '';
	$organization->status = filter_has_var(INPUT_POST, 'status') ? $_POST['status'] : '';
	
	// validate
	if($organization->save()){
		if(filter_has_var(INPUT_POST, 'saveAndClose')){
			echo '<script type="text/javascript">window.location.href = "/wp-admin/admin.php?page=enrm-orgs";</script>';
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
<script type="text/javascript">
	jQuery(function($){
		// Check if we have a logo and show what's appropriate
		if($('#logoImg').attr('src')){
      $('#logoNotSet').hide();
      $('#logoSet').show();
		}else{
      $('#logoNotSet').show();
      $('#logoSet').hide();
		}

		// Logo uploader
		$('.logo-upload').click(function(e){
			e.preventDefault();

			media_uploader = wp.media({
	      frame: 'post',
	      state: 'insert',
	      multiple: false
	    });

	    media_uploader.on('insert', function(){
	      var json = media_uploader.state().get('selection').first().toJSON();
	      $('#logo').val(json.url);
	      $('#logoImg').attr('src', json.url);
	      $('#logoNotSet').hide();
	      $('#logoSet').show();
	    });

	    media_uploader.open();
		});

		// remove the logo
		$('#logoRemove').click(function(e){
      $('#logo').val('');
      $('#logoImg').attr('src', '');
      $('#logoNotSet').show();
      $('#logoSet').hide();
		});
	});
</script>
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
<form name="post" action="" method="post" id="post" enctype="multipart/form-data">
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
				<th scope="row"><label for="logo">Logo</label></th>
				<td>
					<span id="logoSet">
						<img class="logo-upload" id="logoImg" src="<?php echo $organization->logo; ?>"><br><a id="logoRemove" href="javascript:void(0);" style="font-size: 10px;">Remove</a>
						<input id="logo" name="logo" type="hidden" value="<?php echo $organization->logo; ?>">
					</span>
					<span id="logoNotSet">
						<button type="button" class="button logo-upload">Add Logo</button>
					</span>
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
				<th scope="row"><label for="fname">First Name</label></th>
				<td><input name="fname" id="fname" value="<?php echo $organization->fname; ?>" class="regular-text" type="text"></td>
			</tr>
			<tr>
				<th scope="row"><label for="lname">Last Name</label></th>
				<td><input name="lname" id="lname" value="<?php echo $organization->lname; ?>" class="regular-text" type="text"></td>
			</tr>
			<tr>
				<th scope="row"><label for="email">Email Address</label></th>
				<td><input name="email" id="email" value="<?php echo $organization->email; ?>" class="regular-text" type="text"></td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<input name="save" id="save" class="button button-primary" value="Save" type="submit">
		<input name="saveAndClose" id="saveAndClose" class="button button-primary" value="Save &amp; Close" type="submit">
	</p>