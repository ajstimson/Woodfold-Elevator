<?php

/**
 * Template Name: Landing Demo Template
 *
 * This file adds the Landing template. This file assumes that nothing has been moved
 * from the Genesis default.
 *
 * @category   Genesis_Sandbox
 * @package    Templates
 * @subpackage Page
 * @author     Travis Smith
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link       http://wpsmith.net/
 * @since      1.1.0
 */

/** Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) exit( 'Cheating; uh?' );

add_filter( 'body_class', 'gs_add_landing_body_class' );
/**
 * Add page specific body class
 *
 * @param $classes array Body Classes
 * @return $classes array Modified Body Classes
 */
function gs_add_landing_body_class( $classes ) {
   $classes[] = 'landing';
   return $classes;
}

/** Force Layout */
add_filter( 'genesis_pre_get_option_sifte_layout', '__genesis_return_full_width_content' );
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

add_action( 'wp_enqueue_scripts', 'load_flex_scripts' );
function load_flex_scripts() {
   wp_register_script( 'flex-slider-js', 'https://cdnjs.cloudflare.com/ajax/libs/flexslider/2.7.2/jquery.flexslider-min.js', null, null, true );
   wp_enqueue_script('flex-slider-js');
  
}

add_action( 'wp_enqueue_scripts', 'load_slider_js' );
function load_slider_js() {
   wp_register_script( 'slider-script', get_stylesheet_directory_uri() . '/js/slider.js', null, null, true);
   wp_enqueue_script('slider-script');
}

add_filter('acf/format_value/name=form', 'acf_field_do_shortcodes', 10, 3);
function acf_field_do_shortcodes($value, $post_id, $field) {
  if ($value) {
   $value = do_shortcode($value);
  }
  return $value;
}

add_action( 'genesis_before_entry', 'slides' );
function slides(){
   $repeater = get_field('slide');
   $length = count($repeater);
   $slide = array();
   $form = get_field('form');

   echo '<div class="flex-wrapper">';
   echo '<div id="flex-slides" class="flex-item">';
   echo '<div class="flexslider">';
   echo '<ul class="slides">';

   // populate order
   foreach( $repeater as $i => $row ) {
      
      $pre = array();
      $pre['src'] = $row['image'];
      // $pre['txt'] = $row['pre_title'];
      $pre['ttl'] = $row['title'];
      // $pre['lnk'] = $row['link'];
      
      $slide[$i] = build_slide_layer($pre, $i);

      echo '<li>';
      echo $slide[$i];
      echo '</li>';
   
   }

   echo '</ul>';
   echo '</div></div>';
   echo '<div id="slider-form" class="flex-item">' . $form;
   echo '</div></div>';

}

function build_slide_layer($arr, $i){

   $html = '<img src="' . $arr['src'] . '" />';
   $html .= '<p class="flex-caption" id="slide-layer-' . $i . '">';
   $html .= '<span class="flex-wrap">';
   // $html .= '<span class="pre-title">';
   // $html .= $arr['txt'];
   // $html .= '</span>';
   $html .= '<span class="slide-title">';
   $html .= $arr['ttl'];
   $html .= '</span>';
   // $html .= '<span class="slide-link">';
   // $html .= $arr['lnk'];
   // $html .= '</span>';
   $html .= '</span></p>';

   return $html;
}

add_action( 'genesis_before_entry_content', 'entry_title' );

function entry_title(){
   echo '<h1 class="entry-title media-title">' . get_the_title() . '</h1>';
}

genesis();