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
 
$theme_uri = get_template_directory_uri();
 
get_header(); ?>
<div id="templateContent">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-md-<?php echo (is_active_sidebar('blog-sidebar') ? '9' : '12'); ?>" id="mainContent">
                        <?php while (have_posts()) :
                            the_post(); 
                            $page_heading = get_post_custom_values('page_heading', $post->ID);
                            $page_heading = $page_heading[0];
                            ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="thumbnail-wrapper">
                                            <?php if(has_post_thumbnail()):
                                                    the_post_thumbnail();
                                                  else:?>
                                                  <img src="/wp-content/uploads/2014/06/no-thumbnail.jpg" alt="">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="entry-date">
                                            <?php the_date(); ?>
                                        </div>
                                        <h3 class="entry-title">
                                            <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(sprintf(__('Permalink to %s'), the_title_attribute('echo=0'))); ?>" rel="bookmark"><?php the_title(); ?></a>
                                        </h3>
                                        <div class="entry-summary">
                                            <?php the_excerpt(); ?>
                                        </div>
                                    </div>
                            </article>
                        <?php endwhile; ?>
                        <div id="pagination">
                            <?php 
                            global $wp_query;

                            $big = 999999999; // need an unlikely integer

                            echo paginate_links(array(
                                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                                'format' => '?paged=%#%',
                                'current' => max(1, get_query_var('paged')),
                                'total' => $wp_query->max_num_pages
                            )); ?>
                        </div> 
                    </div>
                    <?php if(is_active_sidebar('blog-sidebar')){
                        get_sidebar();
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>