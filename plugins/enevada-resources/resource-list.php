<?php
// this displays the resources screen

// no direct access
defined('ABSPATH') or die('No direct access');

// check if WP List Table is loaded, and if not load it
if(!class_exists('WP_List_Table')){
	require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

// create a class specific to this grid
class en_Resources_List_Table extends WP_List_Table{

	function column_name($item){
	  $actions = array(
      'edit' => sprintf('<a href="?page=%s&amp;action=%s&amp;id=%s">Edit</a>',$_REQUEST['page'],'edit',$item->id),
      'trash' => sprintf('<a href="?page=%s&amp;action=%s&amp;id=%s">Trash</a>',$_REQUEST['page'],'trash',$item->id),
	  );

	  return sprintf('%1$s %2$s', '<a href="?page=' . $_REQUEST['page'] . '&amp;action=edit&amp;id=' . $item->id . '">' . $item->name . '</a>', $this->row_actions($actions));
	}

	function display_rows(){
		foreach($this->items as $item){
			// Nicen up the status
			$status = ucwords($item->status);
			if($status != 'Draft'){
				$status .= 'ed';
			}

			echo '<tr><td>' . $this->column_name($item) . '</td><td>' . $item->display_name . '</td><td><abbr title="' . get_date_from_gmt($item->created, 'Y/m/d g:m:s A') . '">' . get_date_from_gmt($item->created, 'Y/m/d') . '</abbr><br>' . $status . '</td></tr>';
		}
	}

	function get_columns(){
	  $columns = array(
	    'name' => 'Resource Name',
	    'created_by' => 'Author',
	    'created' => 'Date'
	  );
	  return $columns;
	}

	function get_sortable_columns(){
	  $sortable_columns = array(
	    'name' => array('name', false),
	    'created_by' => array('created_by', false),
	    'created' => array('created', false)
	  );
	  return $sortable_columns;
	}

	function prepare_items(){
	  global $wpdb;

		// column settings
	  $columns = $this->get_columns();
	  $hidden = array();
	  $sortable = $this->get_sortable_columns();
	  $this->_column_headers = array($columns, $hidden, $sortable);

	  // pagination settings
	  $current_page = $this->get_pagenum();
	  $per_page = 20;
	  $total_items = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}en_resources WHERE status != 'trash';");
	  $start_at = $per_page * ($current_page - 1);
	  $this->set_pagination_args(array(
	    'total_items' => $total_items,                 
	    'per_page'    => $per_page                     
	  ));

	  // determine sorting
	  $orderby = filter_has_var(INPUT_GET, 'orderby') ? filter_input(INPUT_GET, 'orderby', FILTER_SANITIZE_STRING) : 'r.name';
	  $order = filter_has_var(INPUT_GET, 'order') ? filter_input(INPUT_GET, 'order', FILTER_SANITIZE_STRING) : 'asc';

	  // retrieve the table rows and store them for later use
	  $this->items = $wpdb->get_results("SELECT r.id, r.status, r.name, r.created, u.display_name FROM {$wpdb->prefix}en_resources AS r LEFT JOIN {$wpdb->prefix}users AS u ON r.created_by = u.ID WHERE r.status != 'trash' ORDER BY $orderby $order LIMIT $start_at, $per_page;");
	}
}
?>
<style type="text/css">
.column-created{
	width: 120px;
}
</style>
<?php if($notice): ?>
<div id="notice" class="notice notice-warning"><p id="has-newer-autosave"><?php echo $notice ?></p></div>
<?php endif; ?>
<?php if($message): ?>
<div id="message" class="updated"><p><?php echo $message; ?></p></div>
<?php endif;
// prepare and display the table
$table = new en_Resources_List_Table();
$table->prepare_items(); 
$table->display();
?>