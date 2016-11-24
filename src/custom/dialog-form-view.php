<?php
/**
 * WP Quick Draft or Post Plugin
 *
 * @package     WPQuickPostDraft\Main
 * @since       1.0.0
 * @author      n8finch
 * @link        https://n8finch.com
 * @license     GNU General Public License 2.0+
 */
namespace WPQuickPostDraft\Main;



/**
 * Add add the popup to the footer, will be hidden with jQuery UI
 */
add_action('wp_footer', __NAMESPACE__ . '\load_on_lesson');


/**
 * Determine if this is LearnDash content
 */

function n8f_is_learndash_lesson_content() {

	if(is_singular('sfwd-lessons' )) {
		return true;
	} else {
		return false;
	}
}


/**
 * Determine if this is LearnDash content
 */

function n8f_is_learndash_course_content() {

	if(is_singular('sfwd-lessons' ) || is_singular('sfwd-courses' ) || is_singular('sfwd-topic' )) {
		return true;
	} else {
		return false;
	}
}

function load_on_lesson() {

	//Checks if page is a lesson and if the gif is activated for the lesson

	if ( (n8f_is_learndash_lesson_content() && (get_field('activate_gif_popup_for_this_lesson') === 'yes') ) ) {

		$gif_link = get_the_post_thumbnail_url();
		$gif_phrase = get_field('social_sharing_phrase');
		$gif_hashtags = get_field('gif_hashtags');
		$tw_sharing_phrase = str_replace(' ', '%20', $gif_phrase);
		$tw_sharing_hashtags = str_replace('#', '', $gif_hashtags);
		$post_permalink = get_permalink();

		$fb_link = 'href="http://www.facebook.com/dialog/feed?
								app_id=1317899448273070&
								link='.$post_permalink.'&
								redirect_uri='.$post_permalink.'" target="_blank"';

		$tw_link = 'href="https://twitter.com/intent/tweet?url=' . $post_permalink . '&text='. $tw_sharing_phrase .'&via=startupacademyy&hashtags='. $tw_sharing_hashtags .'" target="_blank"';

		?>
		<div id="n8f-gif-popup" >
  			<h2>Share With your friends!</h2>
  			<img src="<?php echo $gif_link ;?>"/>
  			<p>
  			<a <?php echo $tw_link ; ?> ><span class="dashicons dashicons-twitter"></span></a><a <?php echo $fb_link ; ?> ><span class="dashicons dashicons-facebook"></span></a>
  			</p>
  			<p><?php echo $gif_phrase ; ?> | <?php echo $gif_hashtags ; ?></p>
		</div>

		<?php

	}
}

