<?php
/**
 * The template for displaying single resources in the asset map.
 *
 * @package WordPress
 * @subpackage eNevada
 * @since eNevada 1.0
 */

$categories = en_get_categories();
$orgs = en_get_organizations();
$resources = en_get_resources();

// get the data formatted in a way JavaScript can use it
$rs = array();
foreach($resources as $resource){
	$resource->org = $orgs[$resource->org]->name;
	/*foreach($resource->categories as &$category){
		$category = $categories[$category]->name;
	}*/
	$rs[] = $resource;
}

get_header(); ?>
<style type="text/css">
	#resourceList tr{
		cursor: pointer;
	}
</style>
<div id="templateContent">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="row">
					<div class="col-md-<?php echo ( is_active_sidebar( 'primary' ) ? '9' : '12' ); ?>" id="mainContent">
						<article>
							<header class="entry-header">
								<h1 class="entry-title">
									<a href="/asset-map.html" title="Permalink to Resources" rel="bookmark">Resource List</a>
								</h1>
							</header>
							<div class="entry-content">
								<br>
								<form id="filterForm" style="margin-bottom: 10px;">
									<div class="row">
										<div class="col-sm-6 col-md-5 col-lg-4">
											<input class="form-control" id="search" placeholder="Search" type="text">
										</div>
										<div class="col-sm-6 col-md-5 col-lg-4">
											<select class="form-control" id="category">
												<option value="">--Category--</option>
												<?php foreach($categories as $category): ?>
												<option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
								</form>
								<table class="table table-striped table-hover table-bordered" id="resourceList">
									<thead>
										<tr>
											<th>Resource</th>
											<th>Organization</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($rs as $resource): ?>
										<tr data-categories="<?php echo implode(',', $resource->categories); ?>" data-slug="<?php echo $resource->slug; ?>">
											<td><?php echo $resource->name; ?></td>
											<td><?php echo $resource->org; ?></td>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</article>
					</div>
					<?php if ( is_active_sidebar( 'primary' ) ) {
						get_sidebar('primary');
					} ?>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	jQuery(function($){
		var resources = $('#resourceList tbody tr');

		updateTable();

		// when the category field is changed, update the table
		$('#category').change(function($){
			updateTable();
		});

		// prevent the filter form from being submitted
		$('#filterForm').submit(function(event){
			event.preventDefault();
			updateTable();
		});

		// when the search field is changed, update the table
		$('#search').keyup(function($){
			updateTable();
		});

		/**
		 * Updates the display of the resources
		 */
		function updateTable(){
			var filtered_resources = [];

			// loop through all resources and pull out only those that match the search term, if any
			var category = $('#category').val();
			var search_term = $('#search').val();
			if(search_term.length || category > 0){

				// filter on the search term first
				if(search_term.length){
					// loop through each row
					$.each(resources, function(i,row){
						var rowMatch = false;
						// loop through each cell
						$.each(row.cells, function(index,cell){
							if(cell.innerHTML.toUpperCase().indexOf(search_term.toUpperCase()) != -1){
								rowMatch = true;
							}
						});
						if(rowMatch){
							filtered_resources.push(row);
						}
					});
				}else{
					filtered_resources = resources.slice();
				}

				// filter on the search category
				if(category > 0){
					// work from the previously filtered records
					var tmp_resources = filtered_resources.slice();
					filtered_resources = [];
					
					// loop through each row
					$.each(tmp_resources, function(i,row){
						var rowMatch = false;
						$.each($(row).data('categories').toString().split(','), function(i,category_id){
							if(category_id == category){
								rowMatch = true;
							}
						});
						if(rowMatch){
							filtered_resources.push(row);
						}
					});
				}
			}else{
				filtered_resources = resources.slice();
			}

			// clear all resources from the table
			$('#resourceList tbody').html('');

			// if there are no records, show a placeholder
			if(filtered_resources.length == 0){
				filtered_resources.push('<tr><td colspan="2" style="font-weight: bold; text-align: center;">No resources match your search.</td></tr>');
			}

			// add the resources back into the table
			$.each(filtered_resources, function(i,v){
				$('#resourceList tbody').append(v);
			});
		}

		// navigate to the resource profile when it is clicked
		$('#resourceList tbody tr').click(function(event){
			window.location.href = '/asset-map/' + $(this).data('slug') + '.html';
		});
	});
</script>
<?php get_footer(); ?>