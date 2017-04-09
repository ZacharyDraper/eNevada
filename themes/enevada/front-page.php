<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage eNevada
 * @since eNevada 1.0
 */
 
$theme_uri = get_template_directory_uri();

get_header(); ?>
<div id="fontPageContent">
	<div class="container">
		<div class="row">
			<div class="col-md-<?php echo(is_active_sidebar('primary') ? '9' : '12'); ?>">
				<?php 
				 $args = array(
				    'posts_per_page' => 5,
				    'orderby' => 'post_date',
				    'order' => 'DESC',
				    'post_type' => 'post',
				    'post_status' => 'publish');
				$the_query = new WP_Query($args);
				while($the_query->have_posts()):
					$the_query->the_post();?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="row">
						<div class="col-sm-3">
							<div class="thumbnail-wrapper">
								<?php the_post_thumbnail(); ?>
							</div>
						</div>
						<div class="col-sm-9">
							<div class="entry-date">
								<?php the_date(); ?>
							</div>
							<h3 class="entry-title">
								<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
							</h3>
							<div class="entry-summary">
								<?php the_excerpt(); ?>
							</div>
						</div>
				</article>
				<?php endwhile; ?>
			</div>
			<?php if(is_active_sidebar('primary')){
				get_sidebar('primary');
			} ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>