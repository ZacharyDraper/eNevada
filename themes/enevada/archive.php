<?php
/**
 * The template for displaying Archive pages.
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
<div id="content" class="container">
	<div class="row">
		<div class="col-sm-12">
			<div class="row">
				<div class="col-md-<?php echo ( is_active_sidebar( 'blog-sidebar' ) ? '9' : '12' ); ?>" id="mainContent">
					<?php if ( have_posts() ) : ?>
						<header class="archive-header">
							<h1 class="archive-title"><?php
								if ( is_day() ) :
									printf( __( 'Daily Archives: %s' ), '<span>' . get_the_date() . '</span>' );
								elseif ( is_month() ) :
									printf( __( 'Monthly Archives: %s' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format' ) ) . '</span>' );
								elseif ( is_year() ) :
									printf( __( 'Yearly Archives: %s' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format' ) ) . '</span>' );
								else :
									_e( 'Archives' );
								endif;
							?></h1>
						</header>

						<?php
						/* Start the Loop */
						while ( have_posts() ) : the_post();
							
							/* Include the post format-specific template for the content. If you want to
							 * this in a child theme then include a file called called content-___.php
							 * (where ___ is the post format) and that will be used instead.
							 */
							get_template_part( 'content', get_post_format() );

						endwhile;

						pypeline_content_nav( 'nav-below' );
						?>

					<?php else : ?>
						<?php get_template_part( 'content', 'none' ); ?>
					<?php endif; ?>

				</div>
				<?php if ( is_active_sidebar( 'blog-sidebar' ) ) {
					get_sidebar();
				} ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>