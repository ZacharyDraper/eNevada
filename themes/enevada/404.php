<?php
/**
 * The template for displaying 404 pages (Not Found).
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
				<div class="col-md-<?php echo ( is_active_sidebar( 'sidebar-1' ) ? '9' : '12' ); ?>" id="mainContent">
					<article id="post-0" class="post error404 no-results not-found">
						<header class="entry-header">
							<h1 class="entry-title"><?php _e( 'This is somewhat embarrassing, isn&rsquo;t it?' ); ?></h1>
						</header>

						<div class="entry-content">
							<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.' ); ?></p>
							<?php get_search_form(); ?>
						</div>
					</article>

				</div>
				<?php if ( is_active_sidebar( 'sidebar-1' ) ) {
					get_sidebar();
				} ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>