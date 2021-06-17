<?php

/**
* Template Name: Navigational Page
* Description: Displays content related to product categories and links to specific products
*/

// Temporarily disable Genesis Loop
remove_action( 'genesis_loop', 'genesis_do_loop' );
// Add our custom loop
add_action( 'genesis_loop', 'wf_nav_page_loop' );

function wf_nav_page_loop() {

	echo the_field('slider');
	echo the_field('content_blocks');

}

genesis();