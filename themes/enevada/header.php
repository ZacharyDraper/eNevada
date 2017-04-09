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
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="initial-scale=1.0, width=device-width">
	<title><?php if(is_front_page()){
		bloginfo('name');
		echo ' | '; 
		bloginfo('description');
	}else{
		wp_title('');
	} ?></title>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo $theme_uri; ?>/styles/css/style.css" />
	<link rel="icon" sizes="16x16" type="image/png" href="<?php echo $theme_uri; ?>/img/icons/16x16icon.png">
	<link rel="icon" sizes="24x24" type="image/png" href="<?php echo $theme_uri; ?>/img/icons/24x24icon.png">
	<link rel="icon" sizes="32x32" type="image/png" href="<?php echo $theme_uri; ?>/img/icons/32x32icon.png">
	<link rel="apple-touch-icon" sizes="57x57" type="image/png" href="<?php echo $theme_uri; ?>/img/icons/57x57icon.png">
	<link rel="apple-touch-icon" sizes="72x72" type="image/png" href="<?php echo $theme_uri; ?>/img/icons/72x72icon.png">
	<link rel="icon" sizes="96x96" type="image/png" href="<?php echo $theme_uri; ?>/img/icons/96x96icon.png">
	<link rel="apple-touch-icon" sizes="114x114" type="image/png" href="<?php echo $theme_uri; ?>/img/icons/114x114icon.png">
	<link rel="icon" sizes="128x128" type="image/png" href="<?php echo $theme_uri; ?>/img/icons/128x128icon.png">
	<link rel="icon" sizes="195x195" type="image/png" href="<?php echo $theme_uri; ?>/img/icons/195x195icon.png">
	<?php wp_head(); ?>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
</head>
<body <?php body_class(); ?>>
	<header id="templateHeader">
		<div id="templateHeaderTop">
			<div class="container">
				<div class="row">
					<div class="col-xs-6 col-sm-4 col-md-3">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" id="logo">
							<img src="<?php echo $theme_uri; ?>/img/logo.png" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
						</a>
					</div>
					<div class="col-xs-6 col-md-3 pull-right">
						<div id="socialMedia">
							<a href="https://www.facebook.com/EntrepreneurshipNevada" target="_blank"><img src="<?php echo $theme_uri; ?>/img/facebook.png" alt="Facebook"></a>
							<a href="https://twitter.com/entnev" target="_blank"><img src="<?php echo $theme_uri; ?>/img/twitter.png" alt="Twitter"></a>
						</div>
						<nav class="navbar navbar-default visible-xs visible-sm" role="navigation">
						  <div class="container-fluid">
						    <div class="navbar-header">
						      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mobileNav">
						        <span class="glyphicon glyphicon-th-list"></span><span class="hidden-xs">&nbsp;Menu</span>
						      </button>
						    </div>
							<?php wp_nav_menu(array('theme_location' => 'primary', 'container' => 'div', 'container_class' => 'collapse navbar-collapse', 'container_id' => 'mobileNav', 'depth' => 1, 'menu_class' => 'nav navbar-nav')); ?>			      
						  </div>
						</nav>
					</div>
				</div>
			</div>
		</div>
		<div id="templateHeaderBottom">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<nav class="navbar navbar-default visible-md visible-lg" role="navigation">
						  <div class="container-fluid">
							<?php wp_nav_menu(array('theme_location' => 'primary', 'container' => 'div', 'container_class' => 'collapse navbar-collapse', 'container_id' => 'topNav', 'depth' => 1, 'menu_class' => 'nav navbar-nav')); ?>			      
						  </div>
						</nav>
					</div>
					<?php if(is_front_page()): ?> 
					<div id="banner" class="col-xs-12">
						<div class="row">
							<div class="col-sm-4 hidden-xs">
								<img src="<?php echo $theme_uri; ?>/img/entrepreneurs.jpg" alt="">
							</div>
							<div class="col-xs-12 col-sm-8">
								<h2><?php echo get_option('enevada_banner_heading', ''); ?></h2>
								<p><?php echo get_option('enevada_banner_content', ''); ?></p>
							</div>
						</div>
					</div>
					<hr>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div id="templateHeaderBorder">
			<div class="container">
				<div class="row">
					<div class="col-xs-6 col-xs-offset-3"></div>
				</div>
			</div>
		</div>
	</header>