<?php
/**
 * The template for displaying the footer.
 *
 * @package WordPress
 * @subpackage eNevada
 * @since eNevada 1.0
 */
 
$theme_uri = get_template_directory_uri();
?>
<footer>
	<div id="templateFooterTop">
		<div class="container">
			<div class="row">
				<div class="col-sm-<?php echo(is_active_sidebar('footer-sidebar') ? '9' : '12' ); ?>">
					<div class="row">
						<div class="col-xs-12">
							<h4>Some of our partners</h4>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6 col-md-4">
							<a href="http://www.score-reno.org/" target="_blank">
								<img src="<?php echo $theme_uri; ?>/img/partner-logos/score.png" alt="Score">
							</a>
						</div>
						<div class="col-xs-6 col-md-4">
							<a href="http://edawn.org/" target="_blank">
								<img src="<?php echo $theme_uri; ?>/img/partner-logos/edawn.png" alt="EDAWN">
							</a>
						</div>
						<div class="clearfix visible-xs visible-sm"></div>
						<div class="col-xs-6 col-md-4">
							<a href="http://eoaccess.eonetwork.org/RenoTahoe/Pages/default.aspx" target="_blank">
								<img src="<?php echo $theme_uri; ?>/img/partner-logos/entrepreneurs-organization.png" alt="Entrepreneurs Organization">
							</a>
						</div>
						<div class="clearfix visible-md visible-lg"></div>
						<div class="col-xs-6 col-md-4">
							<a href="http://1mcreno.com" target="_blank">
								<img src="<?php echo $theme_uri; ?>/img/partner-logos/1-million-cups.png" alt="1 Million Cups">
							</a>
						</div>
						<div class="clearfix visible-xs visible-sm"></div>
						<div class="col-xs-6 col-md-4">
							<a href="http://www.tmcc.edu" target="_blank">
								<img src="<?php echo $theme_uri; ?>/img/partner-logos/tmcc.png" alt="TMCC">
							</a>
						</div>
						<div class="col-xs-6 col-md-4">
							<a href="http://www.unr.edu" target="_blank">
								<img src="<?php echo $theme_uri; ?>/img/partner-logos/unr.png" alt="UNR">
							</a>
						</div>
					</div>
				</div>
				<?php if(is_active_sidebar('footer-sidebar')): ?>
					<div id="newsletterSignup" class="col-sm-3 widget-area" role="complementary">
						<?php dynamic_sidebar('footer-sidebar'); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div id="templateFooterBottom">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<div class="row hidden-xs">
						<div class="col-xs-12">
							<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => 'div', 'container_id' => 'footerNav', 'menu_class' => 'nav nav-pills' ) ); ?>			      
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<p id="copyright">&copy; 2013 - <?php echo date('Y'); ?> Entrepreneurship Nevada. All rights reserved.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>
		<?php wp_footer(); ?>
	</body>
</html>