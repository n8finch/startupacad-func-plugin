<?php
/**
 * N8F Functionality Plugin 2.0
 *
 * @package     N8FLessonPopup
 * @author      n8finch
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: N8F Functionality Plugin 2.0
 * Plugin URI:  https://n8finch.com
 * Description: Allows logged in user with appropriate permissions to post quickly on the front end via XML-RPC or the WP REST API if enabled.
 * Version:     1.0.0
 * Author:      Nate Finch
 * Author URI:  https://n8finch.com
 * Text Domain: wp-quick-post-draft
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace N8FLessonPopup;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Cheatin&#8217; uh?' );
}

/**
 * Setup the plugin's constants.
 *
 * @since 1.0.0
 *
 * @return void
 */
function n8f_popup_init_constants() {
	$plugin_url = plugin_dir_url( __FILE__ );
	if ( is_ssl() ) {
		$plugin_url = str_replace( 'http://', 'https://', $plugin_url );
	}

	define( 'n8f_popup_URL', $plugin_url );
	define( 'n8f_popup_DIR', plugin_dir_path( __DIR__ ) );
}

/**
 * Initialize the plugin hooks
 *
 * @since 1.0.0
 *
 * @return void
 */
function n8f_popup_init_hooks() {
	register_activation_hook( __FILE__, __NAMESPACE__ . '\n8f_popup_flush_rewrites' );
	register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
}

/**
 * Flush the rewrites.
 *
 * @since 1.0.0
 *
 * @return void
 */
function n8f_popup_flush_rewrites() {
	n8f_popup_init_autoloader();

	flush_rewrite_rules();
}

/**
 * Kick off the plugin by initializing the plugin files.
 *
 * @since 1.0.0
 *
 * @return void
 */


function n8f_popup_init_autoloader() {

	require_once( 'src/support/autoloader.php' );

	Support\n8f_popup_autoload_files( __DIR__ . '/src/' );
}

/**
 * Enqueue jQuery UI and our scripts and styles
 *
 * @since 1.0.0
 *
 * @return void
 */
function n8f_popup_add_these_plugin_styles_and_scripts() {

	//Enque styles and scripts for Lesson Popup

	if(get_field('activate_gif_popup_for_this_lesson') === 'yes') {

		//enqueue main styles and scripts
		wp_enqueue_style( 'included-styles', n8f_popup_URL . 'css/included_styles.css' );
		wp_enqueue_script( 'included-js', n8f_popup_URL . 'js/included_js.js', array(
			'jquery',
			'jquery-ui-dialog'
		), false, false );

		//use local data for actually posting to the admin
		wp_localize_script( 'included-js', 'n8f_popup_submit_info', array(
				'lesson_popup_active' => get_field('activate_gif_popup_for_this_lesson')
			)
		);

		wp_enqueue_media();
	}






}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\n8f_popup_add_these_plugin_styles_and_scripts' );

/**
 * Launch the plugin
 *
 * @since 1.0.0
 *
 * @return void
 */
function n8f_popup_launch() {

		n8f_popup_init_autoloader();

}


add_action( 'init', __NAMESPACE__ . '\n8f_popup_init_plugin_files', 999 );

function n8f_popup_init_plugin_files() {
	if ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
		n8f_popup_init_constants();
		n8f_popup_init_hooks();
		n8f_popup_launch();
	}
}


/**
 * Add Extra Footer Widget Area
 */
function n8f_register_new_footer_widget() {

	register_sidebar( array(
		'name'          => 'Pre-Footer Widget Area',
		'id'            => 'n8f_footer_widget',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="rounded">',
		'after_title'   => '</h2>',
	) );

}
add_action( 'widgets_init', __NAMESPACE__ . '\n8f_register_new_footer_widget', 70 );


add_action( 'get_footer', __NAMESPACE__ . '\n8f_do_extra_footer_widget' );

function n8f_do_extra_footer_widget() {

	if ( is_active_sidebar( 'n8f_footer_widget' ) ) :
	?>

	<section id="mk-footer" background-color=white class="top" role="contentinfo" itemscope="itemscope" itemtype="https://schema.org/WPFooter">
	    <div class="footer-wrapper mk-grid">
	        <div class="mk-padding-wrapper">


								<div id="" class="primary-sidebar widget-area" role="complementary">
									<?php dynamic_sidebar( 'n8f_footer_widget' ); ?>
								</div><!-- #primary-sidebar -->


	      	    <div class="clearboth"></div>
	        </div>
	    </div>
	</section>

	<?php endif;
}
