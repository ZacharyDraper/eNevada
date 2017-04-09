<?php
/**
 * The template for displaying Search Results pages.
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
				<div class="col-md-<?php echo ( is_active_sidebar( 'primary' ) ? '9' : '12' ); ?>" id="mainContent">
				<?php if ( have_posts() ) : ?>

					<header class="page-header">
						<h1 class="page-title"><?php printf( __( 'Search Results for: %s' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
					</header>

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'content', get_post_format() ); ?>
					<?php endwhile; ?>

					<?php intellitext_content_nav( 'nav-below' ); ?>

				<?php else : ?>

					<article id="post-0" class="post no-results not-found">
						<header class="entry-header">
							<h1 class="entry-title"><?php _e( 'Nothing Found' ); ?></h1>
						</header>

						<div class="entry-content">
							<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.' ); ?></p>
							<?php get_search_form(); ?>
						</div>
					</article>

				<?php endif; ?>

			</div>
			<?php if ( is_active_sidebar( 'primary' ) ) {
				get_sidebar('primary');
			} ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>	