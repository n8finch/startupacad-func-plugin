<?php

//Add Facebook Pixel
add_action('wp_head', 'n8f_add_facebook_pixel' );

function n8f_add_facebook_pixel() {
	?><!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1614430918789611');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1614430918789611&ev=PageView&noscript=1"
/></noscript>
<!-- DO NOT MODIFY -->
<!-- End Facebook Pixel Code -->
<?php
}



//Move Facebook comments after LearnDash content
remove_filter ('the_content', 'fbcommentbox', 100);
add_filter ('the_content', 'fbcommentbox', 9999);


function n8f_add_prev_link_to_confirmation_page($content) {

	$n8f_previous_content_button = '<p><a class="back-history return-to-previous-page-button" href="javascript: history.go(-1)"><button>Return to Previous Page</button></a></p>';

	if (is_page('confirmation') || is_page('member-profile')) {
		return $n8f_previous_content_button . $content . $n8f_previous_content_button;
	} else {
		return $content;
	}
}


add_filter( 'show_quiz_continue_buttom_on_fail', 'n8f_show_quiz_continue_buttom_on_fail_proc', 10, 2 );
function n8f_show_quiz_continue_buttom_on_fail_proc( $show_button = false, $quiz_id = 0 ) {
	$show_button = true;
	return $show_button;
}

add_filter('learndash_quiz_continue_link', 'n8f_change_quiz_complete_link', 5, 2 );

function n8f_change_quiz_complete_link($returnLink, $url) {

	$returnLink = '<a id="quiz_continue_link" href="https://www.startupacademy.org/sessions/">Go to Sessions Page</a>';

	return $returnLink;
}


add_shortcode('courses_by_day_button', 'n8f_show_learndash_button_based_on_course' );

function n8f_show_learndash_button_based_on_course() {

	global $post;

	$post_type = get_post_type($post);
	$post_meta = get_post_meta($post->ID, "_" . $post_type);
	$post_meta_key = $post_type . '_course';
	$course_id = $post_meta[0][$post_meta_key];

	if ($post_type === 'sfwd-lessons' || $post_type === 'sfwd-topic' || $post_type === 'sfwd-quiz') {
		$day = 1;
		$link = '/sessions/';

		//Assuming the following:
		// Day 1 = 64
		// Day 2 = 71
		// Day 3 = 72

		switch ($course_id) {
			case '64':
				$day = 'Continue to Day 2';
				$link = '/sessions/day-2-launch-your-concept/';
				break;

			case '71':
				$day = 'Continue to Day 3';
				$link = '/sessions/day-3-become-a-startup/';
				break;

			default:
				$day = 'Go to Sessions Page';
				break;
		}

		$output = '<div class="widget-cta-and-button" style="text-align: center; font-size: 18px;">
							    <a class="wl-initialized" href="' . $link . '">
							    <button>' . $day . '</button>
							    </a>
							</div>';

		return $output;

	} else  {

		return '';

	}

}





/**
 * Add query and shortcode for "Continue where you left off" button
 */
add_action( 'wp_head', 'n8f_find_last_known_learndash_page' );
add_shortcode( 'n8f-learndash-resume', 'n8f_learndash_resume' );

/**
 *Adding wp_head action so that we capture the type of post / page user is on and add that to wordpress options table.
 */
function n8f_find_last_known_learndash_page() {

	$user = wp_get_current_user();

	if ( is_user_logged_in() ) {

		/* declare $post as global so we get the post->ID of the current page / post */
		global $post;
		/* Limit the plugin to LearnDash specific post types */
		$learn_dash_post_types = apply_filters(
			'last_known_learndash_post_types',
			array(
				'sfwd-courses',
				'sfwd-lessons',
				'sfwd-topic',
				'sfwd-quiz',
				'sfwd-certificates',
				'sfwd-certificates',
				'sfwd-assignment',
			)
		);

		if ( is_singular( $learn_dash_post_types ) ) {
			update_user_meta( $user->ID, 'learndash_last_known_page', $post->ID );
		}
	}
}


/**
 *Adding [uo-learndash-resume] shortcode functionality which can be used anywhere on the website to take user back to last known page of LearnDash.
 */
function n8f_learndash_resume() {

	$user = wp_get_current_user();

	if ( is_user_logged_in() ) {

		$last_know_page_id = get_user_meta( $user->ID, 'learndash_last_known_page', true );
		$last_know_post_object = get_post($last_know_page_id);


		// Make sure the post exists and that the user hit a page that was a post
		// if $last_know_page_id returns '' then get post will return current pages post object
		// so we need to make sure first that the $last_know_page_id is returning something and
		// that the something is a valid post
		if ( '' !== $last_know_page_id && null !== $last_know_post_object) {
			$post_type        = $last_know_post_object->post_type; // getting post_type of last page.
			$label            = get_post_type_object( $post_type ); // getting Labels of the post type.
			$title            = $last_know_post_object->post_title;
			$resume_link_text = 'Continue where you left off...';

			// Resume Link Text
			// $link_text = get_settings_value( 'learn-dash-resume-button-text' );

			if ( strlen( trim( $link_text ) ) ) {
				$resume_link_text = $link_text;
			}

			ob_start();
			printf(
				'<a href="%s" title="%s" class="%s wl-initialized"><button class="">Continue where you left off...</button></a>',
				get_permalink( $last_know_page_id ),
				esc_attr(
					sprintf(
						esc_html_x( 'Resume %s: %s', 'LMS shortcode Resume link title "Resume post_type_name: Post_title ', 'uncanny-learndash-toolkit' ),
						$label->labels->singular_name,
						$title
					)
				),
				esc_attr( $css_classes ),
				esc_attr( $resume_link_text )
			);

			$resume_link = ob_get_contents();
			ob_end_clean();

			return $resume_link;
		}

	}

	return '';
}
