<?php
/**
 * The template for displaying a single post.
 *
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage eNevada
 * @since eNevada 1.0
 */

get_header(); ?>
<div id="templateContent">
	<div class="container">
		<div class="row">
			<div class="col-md-<?php echo(is_active_sidebar('blog-sidebar') ? '9' : '12' ); ?>" id="mainContent">
				<?php while (have_posts()) : the_post(); ?>
					<?php get_template_part('content', get_post_format()); ?>
				<?php endwhile; ?>
			</div>
			<?php if(is_active_sidebar('blog-sidebar')){
				get_sidebar();
			} ?>
		</div>
	</div>
</div>		
<?php get_footer(); ?>