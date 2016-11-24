<?php

/**
 * RSS Feed Aggregator
 */

include_once( ABSPATH . WPINC . '/feed.php' );


//Add Short Codes
add_shortcode('n8f-rss-agg-sa', 'n8f_sa_rss_shortcode' );
add_shortcode('n8f-rss-agg-tc', 'n8f_tc_rss_shortcode' );
add_shortcode('n8f-rss-agg-mash', 'n8f_mash_rss_shortcode' );


/**
 * TechCrunch Shortcode
 */

function n8f_sa_rss_shortcode() {


	$args = array(
		'posts_per_page' => 5,

		);

	$myposts = get_posts( $args );

	foreach ( $myposts as $post ) : setup_postdata( $post );
		$post_ID = $post->ID;
		$post_permalink = $post->guid;
		$post_title = $post->post_title;
		$post_content = strip_tags($post->post_content);
		$post_content = substr($post_content, 0, 250);
		$post_thumb = get_the_post_thumbnail_url($post_ID);

		?>
		<div class="n8f-rss-div">
	            		<a href="<?php echo $post_permalink  ?>" target="_blank"><h4><?php echo $post_title; ?></h4></a>
	            		<img src="<?php echo $post_thumb; ?>">
	            		<p><?php echo $post_content; ?>... | <a href="<?php echo $post_permalink; ?>">Read More...</a></p>
	            </div>
	            <hr/>
		<?php endforeach;

	wp_reset_postdata();



} //end shortcode



/**
 * TechCrunch Shortcode
 */

function n8f_tc_rss_shortcode() {

		// Get a SimplePie feed object from the specified feed source.
	$rss = fetch_feed( 'https://techcrunch.com/startups/feed/' );

	$maxitems = 0;

	if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly

	    // Figure out how many total items there are, but limit it to 5.
	    $maxitems = $rss->get_item_quantity( 5 );

	    // Build an array of all the items, starting with element 0 (first element).
	    $rss_items = $rss->get_items( 0, $maxitems );

	endif;

	?>

	    <?php if ( $maxitems == 0 ) : ?>
	        <li><?php _e( 'No items', 'my-text-domain' ); ?></li>
	    <?php else : ?>
	        <?php // Loop through each feed item and display each item as a hyperlink. ?>
	        <?php foreach ( $rss_items as $item ) : ?>
	        		<?php

	        		if ($enclosure = $item->get_enclosure())
								{
									$thumbnail = $enclosure->get_thumbnail();
								}

							$description = $item->get_description();
							$pos = strpos($description, '&nbsp;', 1);
							$substr = substr($description, $pos);

							$title = $item->get_title();
							$link = $item->get_link();

							?>

	            <div class="n8f-rss-div">
	            		<a href="<?php echo $link; ?>" target="_blank"><h4><?php echo $title; ?></h4></a>
	            		<p><?php echo $description; ?></p>
	            </div>
	            <hr/>

	        <?php endforeach; ?>
	    <?php endif; ?>

	<?php
} //end shortcode



/**
 * TechCrunch Shortcode
 */

function n8f_mash_rss_shortcode() {

		// Get a SimplePie feed object from the specified feed source.
	$rss = fetch_feed( 'http://mashable.com/category/social-media/feed/' );

	$maxitems = 0;

	if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly

	    // Figure out how many total items there are, but limit it to 5.
	    $maxitems = $rss->get_item_quantity( 5 );

	    // Build an array of all the items, starting with element 0 (first element).
	    $rss_items = $rss->get_items( 0, $maxitems );

	endif;

	?>

	    <?php if ( $maxitems == 0 ) : ?>
	        <li><?php _e( 'No items', 'my-text-domain' ); ?></li>
	    <?php else : ?>
	        <?php // Loop through each feed item and display each item as a hyperlink. ?>
	        <?php foreach ( $rss_items as $item ) : ?>
	        		<?php

	        		if ($enclosure = $item->get_enclosure())
								{
									$thumbnail = $enclosure->get_thumbnail();
								}

							$description = $item->get_description();
							$pos = strpos($description, '&nbsp;', 1);
							$substr = substr($description, $pos);

							$title = $item->get_title();
							$link = $item->get_link();

							?>

	            <div class="n8f-rss-div">
	            		<a href="<?php echo $link; ?>" target="_blank"><h4><?php echo $title; ?></h4></a>
	            		<p><?php echo $description; ?></p>
	            </div>
	            <hr/>

	        <?php endforeach; ?>
	    <?php endif; ?>

	<?php
} //end shortcode
