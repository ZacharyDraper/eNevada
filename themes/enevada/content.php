<?php
/**
 * The template for displaying the references page.
 *
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage eNevada
 * @since eNevada 1.0
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php the_breadcrumbs(); ?>
	<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
	<div class="featured-post">
		<?php _e( 'Featured post' ); ?>
	</div>
	<?php endif; ?>
	<header class="entry-header">
		<?php if ( is_single() ) : ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php else : ?>
		<h1 class="entry-title">
			<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h1>
		<?php endif; // is_single() ?>
	</header>
	<div class="entry-date">
		<?php the_date(); ?>
	</div>
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
	</footer>
	<?php endif; ?>
	<div id="commentsWrapper">
		<?php comments_template(); ?> 
	</div>
</article>