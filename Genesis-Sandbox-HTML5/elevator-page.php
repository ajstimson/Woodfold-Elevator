<?php
/**
* Template Name: Elevator Page Template
*/

// Add our custom loop
// add_action( 'genesis_before_entry_content', 'elevator_page' );
function elevator_page() {
  if ( is_page(1371)){
    $elevator_content = get_home_path() . '/elevator-gates/index.php';
    include $elevator_content;
  } else {
    $gates_content = get_home_path() . '/elevator-gates/gates.php';
    include $gates_content;
  }
	
}

function get_home_path() {
  $home    = set_url_scheme( get_option( 'home' ), 'http' );
  $siteurl = set_url_scheme( get_option( 'siteurl' ), 'http' );
  if ( ! empty( $home ) && 0 !== strcasecmp( $home, $siteurl ) ) {
    $wp_path_rel_to_home = str_ireplace( $home, '', $siteurl ); /* $siteurl - $home */
    $pos                 = strripos( str_replace( '\\', '/', $_SERVER['SCRIPT_FILENAME'] ), trailingslashit( $wp_path_rel_to_home ) );
    $home_path           = substr( $_SERVER['SCRIPT_FILENAME'], 0, $pos );
    $home_path           = trailingslashit( $home_path );
  } else {
    $home_path = ABSPATH;
  }

  return str_replace( '\\', '/', $home_path );
}
genesis();