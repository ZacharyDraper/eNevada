<?php
/**
 * The template for displaying the a single page.
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
			<div class="col-sm-12">
				<div class="row">
					<div class="col-md-<?php echo ( is_active_sidebar( 'primary' ) ? '9' : '12' ); ?>" id="mainContent">
						<?php while ( have_posts() ) :
							the_post(); 
							$page_heading = get_post_custom_values('page_heading', $post->ID);
							$page_heading = $page_heading[0];
							?>
							<article class="collapsed-margins" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
								<div class="featured-post">
									<?php _e( 'Featured post' ); ?>
								</div>
								<?php endif; ?>
								<header class="entry-header">
									<?php the_post_thumbnail(); ?>
									<?php if ( is_single() ) : ?>
									<h1 class="entry-title"><?php if( empty( $page_heading ) ){ the_title(); }else{ echo $page_heading; } ?></h1>
									<?php else : ?>
									<h1 class="entry-title">
										<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php if( empty( $page_heading ) ){ the_title(); }else{ echo $page_heading; } ?></a>
									</h1>
									<?php endif; // is_single() ?>
								</header>
							
								<?php if ( is_search() ) : // Only display Excerpts for Search ?>
								<div class="entry-summary">
									<?php the_excerpt(); ?>
								</div>
								<?php else : ?>
								<div class="entry-content">
									<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>' ) ); ?>
									<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:' ), 'after' => '</div>' ) ); ?>
								</div>
								<?php endif; ?>
							
								<?php if(!is_page()): ?>
								<footer class="entry-meta">
									<?php edit_post_link( __( 'Edit' ), '<span class="edit-link">', '</span>' ); ?>
									<?php if ( is_singular() && get_the_author_meta( 'description' ) && is_multi_author() ) : // If a user has filled out their description and this is a multi-author blog, show a bio on their entries. ?>
										<div class="author-info">
											<div class="author-avatar">
												<?php echo get_avatar( get_the_author_meta( 'user_email' ), 68 ); ?>
											</div>
											<div class="author-description">
												<h2><?php printf( __( 'About %s' ), get_the_author() ); ?></h2>
												<p><?php the_author_meta( 'description' ); ?></p>
												<div class="author-link">
													<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
														<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>' ), get_the_author() ); ?>
													</a>
												</div>
											</div>
										</div>
									<?php endif; ?>
								</footer>
								<?php endif; ?>
							</article>
						<?php endwhile;
						$args = array( 
							'order' => 'ASC',
							'orderby' => 'title',
			               	'post_type' => 'eresources',
							'resource_category' => 'Take A Class',
			               	'posts_per_page' => 100,
			              );
						$the_query = new WP_Query($args);
						while($the_query->have_posts()):
							$the_query->the_post();
						?>
						<article class="collapsed-margins" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<hr />
							<div class="row">
								<div class="col-sm-3">
									<div class="thumbnail-wrapper">
										<?php the_post_thumbnail(); ?>
									</div>
								</div>
								<div class="col-sm-9">
									<h3 class="entry-title">
										<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
									</h3>
									<div class="entry-summary">
										<?php the_content(); ?>
									</div>
									<div class="entry-link">
									<a href="<?php echo get_post_custom_values('website', $post->ID)[0]; ?>" target="_blank" class="btn btn-primary">Visit Website</a>
									</div>
								</div>
						</article>
						<?php endwhile; ?>
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