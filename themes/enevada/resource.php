<?php
/**
 * The template for displaying single resources in the asset map.
 *
 * @package WordPress
 * @subpackage eNevada
 * @since eNevada 1.0
 */

// localize the resource object
$resource = $_SESSION['en_resource'];
unset($_SESSION['en_resource']);

// Make sure every website starts with http://
if(strpos($resource->website, 'http://') === false && strpos($resource->website, 'https://') === false){
	$web_address = 'http://' . $resource->website;
}else{
	$web_address = $resource->website;
}

get_header(); ?>
<div id="templateContent">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="row">
					<div class="col-md-<?php echo ( is_active_sidebar( 'primary' ) ? '9' : '12' ); ?>">
						<div id="mainContent">
							<article>
								<?php if($resource->id > 0): ?>
								<header class="entry-header">
									<div class="row">
										<div class="col-md-8 col-lg-9">
											<h1 class="entry-title"><?php echo $resource->name; ?></h1>
											<p><b><?php echo en_get_organization_name($resource->org); ?></b></p>
										</div>
										<div class="col-md-4 col-lg-3">
											<?php if($resource->website):
											 ?>
												<p class="hidden-xs hidden-sm" style="text-align: right;"><a class="btn btn-primary" rel="noopener noreferrer" target="_blank" title="Visit Website" href="<?php echo $web_address; ?>">Visit Website</a></p>
											<?php endif;
											if($resource->email): ?>
												<p class="hidden-xs hidden-sm" style="text-align: right;"><a class="btn btn-primary" rel="noopener noreferrer" target="_blank" title="Email <?php echo $resource->name; ?>" href="mailto:<?php echo $resource->email; ?>">Email</a></p>
											<?php endif; ?>
										</div>
									</div>
								</header>
								<div class="entry-content">
									<p><?php echo $resource->description; ?></p>
									<?php if($resource->telephone): ?>
									<p>Reach out to <?php echo $resource->contact_name . ' of ' . $resource->name . ' at ' . $resource->telephone; ?> to learn more.</p>
									<?php endif;
									if($resource->website):
										?>
									<p class="hidden-md hidden-lg"><a class="btn btn-primary" rel="noopener noreferrer" target="_blank" title="Visit Website" href="<?php $web_address; ?>">Visit Website</a></p>
									<?php
									endif;
									if($resource->email): ?>
									<p class="hidden-md hidden-lg"><a class="btn btn-primary" rel="noopener noreferrer" target="_blank" title="Email <?php echo $resource->name; ?>" href="mailto:<?php echo $resource->email; ?>">Email</a></p>
									<?php endif; 
									$categories = array();
									foreach($resource->categories as $category){
										$categories[] = en_get_category_name($category);
									}
									sort($categories);
									?>
									<p style="margin-bottom: -20px;"><small>This resource can help you with: <?php echo implode(', ', $categories); ?></small></p>
								</div>
							<?php else: ?>
								<header class="entry-header">
									<h1 class="entry-title">
										Resource Not Found
									</h1>
								</header>
								<div class="entry-content">
									<p>We're sorry, but the resource you are looking for could not be found.</p>
								</div>
							<?php endif; ?>
							</article>
						</div>
						<a class="btn btn-default" href="/asset-map.html" style="margin-top: 15px;">< Back to Resource List</a>
					</div>
					<?php if ( is_active_sidebar( 'primary' ) ) {
						get_sidebar('primary');
					} ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>