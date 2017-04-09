<?php
/**
 * Sets up theme defaults and registers the various WordPress features that
 * eNevada supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since eNevada 1.0
 */
function eNevada_setup(){
	// This theme uses wp_nav_menu() in one location.
	register_nav_menu('primary', __('Primary Menu'));
}

/**
* Load scripts
*/

function eNevada_scripts(){
	wp_enqueue_script(
		'typekit',
		'//use.typekit.net/kia2sco.js'
	);
	wp_deregister_script('jquery');
	wp_register_script(
		'jquery',
		get_template_directory_uri() . '/js/min/jquery.min.js',
		false,
		'2.1.1'
	);
	wp_enqueue_script('jquery');
	wp_enqueue_script(
		'main',
		get_template_directory_uri() . '/js/min/main.min.js',
		array( 'jquery' )
	);
}

/**
* Sets the og:image meta tag on the home page so it is not blank
*/
function enevada_fb_home_image($tags){
    // Remove the default blank image added by Jetpack
    unset($tags['og:image']);

    $fb_home_img = $tags['og:url'] . 'wp-content/themes/enevada/img/social-logo.jpg';
    $tags['og:image'] = esc_url($fb_home_img);

    return $tags;
}
add_filter('jetpack_open_graph_tags', 'enevada_fb_home_image');

/**
 * Displays navigation to next/previous pages when applicable.
 *
 * @since eNevada 1.0
 */
function enevada_content_nav($html_id) {
	global $wp_query;

	$html_id = esc_attr($html_id);

	if($wp_query->max_num_pages > 1): ?>
		<nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">
			<h3 class="sr-only"><?php _e('Post navigation'); ?></h3>
			<div class="nav-previous alignleft"><?php next_posts_link( __('<span class="meta-nav">&larr;</span> Older posts')); ?></div>
			<div class="nav-next alignright"><?php previous_posts_link( __('Newer posts <span class="meta-nav">&rarr;</span>')); ?></div>
		</nav>
	<?php endif;
}

/**
* Sets the length of excerpts (in words)
*/
function custom_excerpt_length( $length ) {
	return 70;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

/**
* Sets the read more link
*/
function new_excerpt_more( $more ) {
	return '...<br><br><a class="read-more btn btn-primary" href="'. get_permalink( get_the_ID() ) . '">Read More</a>';
}
add_filter( 'excerpt_more', 'new_excerpt_more' );

/**
 * Creates all necessary custom post types
 */
function enevada_create_post_type() {
	register_post_type('eresources',
		array(
			'description' => 'A custom post type for resources appearing on the website. One resource per post.',
			'has_archive' => false,
			'labels' => array(
				'name' => __('Resources'),
				'singular_name' => __('Resource'),
			),
			'menu_icon' => 'dashicons-book-alt',
			'public' => true,
			'supports' => array('title','editor','thumbnail'),
			'taxonomies' => array('resource_category')
		)
	);
}

/**
 * Add a custom taxonomy for resource categories
 */
function resources_init() {
	// create a new taxonomy
	register_taxonomy(
		'resource_category',
		'post',
		array(
			'hierarchical' => true,
			'label' => __('Category')
		)
	);
}
add_action('init', 'resources_init');

/**
 * Register our sidebars and widgetized areas.
 */
function enevada_widgets_init(){
	register_sidebar(array(
		'name' => 'Blog Sidebar',
		'id' => 'blog-sidebar',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	));
	register_sidebar(array(
		'name' => 'Footer Sidebar',
		'id' => 'footer-sidebar',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	));
	register_sidebar(array(
		'name' => 'Main Sidebar',
		'id' => 'primary',
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	));
}

// actions
add_action('init', 'enevada_create_post_type');
add_action('after_setup_theme', 'enevada_setup');
add_action('widgets_init', 'enevada_widgets_int');
add_action('admin_menu', 'enevada_create_menu');
add_action('wp_enqueue_scripts', 'enevada_scripts');
add_action('widgets_init', 'enevada_widgets_init');

// thumbnail support
add_theme_support('post-thumbnails',array('post','eresources')); 
set_post_thumbnail_size(261,178,true);

function enevada_create_menu(){

	//create new top-level menu
	add_menu_page('eNevada Theme Settings', 'eNevada Settings', 'administrator', __FILE__, 'enevada_settings_page','dashicons-admin-generic');

	//call register settings function
	add_action('admin_init', 'register_enevada_settings');
}

function register_enevada_settings(){
	//register our settings
	register_setting('enevada-settings-group', 'enevada_banner_heading');
	register_setting('enevada-settings-group', 'enevada_banner_content');
}

function the_breadcrumbs(){
    global $post;
    echo '<div id="breadcrumbs"><ul>';
    if (!is_home()) {
        echo '<li><a href="';
        echo get_option('home');
        echo '">';
        echo 'Home';
        echo '</a></li><li class="separator"><img alt="/" src="' . get_theme_root_uri() . '/enevada/img/arrow.png"></li>';
        if (is_category() || is_single()) {
            echo '<li>';
            the_category(' </li><li class="separator"><img alt="/" src="' . get_theme_root_uri() . '/enevada/img/arrow.png"></li><li> ');
            if (is_single()) {
                echo '</li><li class="separator"><img alt="/" src="' . get_theme_root_uri() . '/enevada/img/arrow.png"></li><li>';
                the_title();
                echo '</li>';
            }
        } elseif (is_page()) {
            if($post->post_parent){
                $anc = get_post_ancestors( $post->ID );
                $title = get_the_title();
                foreach ( $anc as $ancestor ) {
                    $output = '<li><a href="'.get_permalink($ancestor).'" title="'.get_the_title($ancestor).'">'.get_the_title($ancestor).'</a></li> <li class="separator">/</li>';
                }
                echo $output;
                echo '<strong title="'.$title.'"> '.$title.'</strong>';
            } else {
                echo '<li><strong> '.get_the_title().'</strong></li>';
            }
        }
    }
    elseif (is_tag()) {single_tag_title();}
    elseif (is_day()) {echo"<li>Archive for "; the_time('F jS, Y'); echo'</li>';}
    elseif (is_month()) {echo"<li>Archive for "; the_time('F, Y'); echo'</li>';}
    elseif (is_year()) {echo"<li>Archive for "; the_time('Y'); echo'</li>';}
    elseif (is_author()) {echo"<li>Author Archive"; echo'</li>';}
    elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {echo "<li>Blog Archives"; echo'</li>';}
    elseif (is_search()) {echo"<li>Search Results"; echo'</li>';}
    echo '</ul></div>';
}

function enevada_settings_page(){
?>
<div class="wrap">
<h2>eNevada Theme Settings</h2>

<form method="post" action="options.php">
    <?php settings_fields('enevada-settings-group'); ?>
    <?php do_settings_sections('enevada-settings-group'); ?>
	<h3>Front Page Banner</h3>
    <div style="border: 1px solid black; padding: 10px;">
	    <table class="form-table">
	        <tr valign="top">
	        	<th scope="row">Heading</th>
	        	<td><input type="text" name="enevada_banner_heading" value="<?php echo get_option('enevada_banner_heading'); ?>" /></td>
	        </tr>
	        <tr valign="top">
	        	<th scope="row">Content</th>
	        	<td>
	        		<textarea name="enevada_banner_content"><?php echo get_option('enevada_banner_content'); ?></textarea>
	        	</td>
	        </tr>
	    </table>
	</div>
    <?php submit_button(); ?>
</form>
</div>
<?php } ?>
