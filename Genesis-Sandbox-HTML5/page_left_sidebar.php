<?php

/**
 * Template Name: Left Sidebar Template
 *
 * This file adds the Landing template. This file assumes that nothing has been moved
 * from the Genesis default.
 *
 * @category   Genesis_Sandbox
 * @package    Templates
 * @subpackage Page
 * @author     Andrew Stimson
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link       http://applejuice.codes/
 * @since      1.1.0
 */

/** Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) exit( 'Cheating; uh?' );

add_filter( 'body_class', 'gs_add_left_sidebar_body_class' );
/**
 * Add page specific body class
 *
 * @param $classes array Body Classes
 * @return $classes array Modified Body Classes
 */
function gs_add_left_sidebar_body_class( $classes ) {
   $classes[] = 'left-sidebar';
   return $classes;
}

add_action( 'wp_enqueue_scripts', 'add_sidebar_style' );
function add_sidebar_style() {
    wp_enqueue_style( 'sidebar', get_stylesheet_directory_uri() .'/css/sidebar.css', 20);
}

add_filter('acf/format_value/name=form', 'acf_field_do_shortcodes', 10, 3);
function acf_field_do_shortcodes($value, $post_id, $field) {
  if ($value) {
   $value = do_shortcode($value);
  }
  return $value;
}

add_action('genesis_before_entry_content', 'entry_content');
function entry_content(){

   marquee_section();
   echo '<div class="sidebar-entry-wrap">';
   dynamic_sidebar( 'left-sidebar' );
   echo '<div class="sidebar-adjacent-wrap">';
   entry_title();
   section_1();
   echo '</div></div>';
   content_break();
   section_2();
   pre_footer();

}

function marquee_section(){
      // Check value exists.
      if( have_rows('marquee') ):

         // Loop through rows.
         while ( have_rows('marquee') ) : the_row();
   
            // Case: Paragraph layout.
            if( get_row_layout() == 'twocolumn' ):
               $column_1 = get_sub_field('column_1');
               $column_2 = get_sub_field('column_2');
   
               echo '<section class="marquee_section">';
               echo '<div class="left-half">';
               echo $column_1;
               echo '</div>';
               echo '<div class="right-half"><div class="right-wrap">';
               echo $column_2;
               echo '</div></div>';
               echo '</section>';
   
            endif;
   
         // End loop.
         endwhile;
      endif;
}

function entry_title(){
   echo '<h3 class="entry-title small-title">' . get_field('template_entry_title') . '</h3>';
   echo '<h4 class="entry-title large-title">' . get_field('template_entry_subtitle') . '</h4>';
}
 
//Function to output my custom sidebar
function do_left_sidebar() {
   dynamic_sidebar( 'left-sidebar' );
}


function section_1(){
   // Check value exists.
   if( have_rows('section_1') ):

      // Loop through rows.
      while ( have_rows('section_1') ) : the_row();

         // Case: Paragraph layout.
         if( get_row_layout() == 'twocolumns' ):
            $column_1 = get_sub_field('column_1');
            $column_2 = get_sub_field('column_2');

            echo '<section class="section_columns">';
            echo '<div class="left-half">';
            echo $column_1;
            echo '</div>';
            echo '<div class="right-half">';
            echo $column_2;
            echo '</div>';
            echo '</section>';

         endif;

      // End loop.
      endwhile;
   endif;
}

function content_break(){
   echo '<section class="content-divider">';
   echo '<div class="divider-wrap">';
   echo get_field('break_1');
   echo '</div></section>';
}

function section_2(){
   echo '<div class="portfolio">';
   echo '<div class="portfolio-wrap">';
   // Check value exists.
   if( have_rows('section_2') ):
     
      
      // Loop through rows.
      while ( have_rows('section_2') ) : the_row();
         echo '<div class="portfolio-image">';
         echo '<img src="' . get_sub_field('portfolio_image') . '" >';
         echo '</div>';

      // End loop.
      endwhile;

   endif;
   echo '</div></div>';
}

function pre_footer(){
   echo '<section class="pre-footer">';
   echo '<div class="pre-footer-wrap">';

   // Check value exists.
   if( have_rows('pre_footer') ):

      // Loop through rows.
      while ( have_rows('pre_footer') ) : the_row();

         // Case: Paragraph layout.
         if( get_row_layout() == 'twocolumns' ):
            $column_1 = get_sub_field('column_1');
            $column_2 = get_sub_field('column_2');

            echo '<section class="section_columns">';
            echo '<div class="left-half">';
            echo $column_1;
            echo '</div>';
            echo '<div class="right-half">';
            echo $column_2;
            echo '</div>';
            echo '</section>';

         endif;

      // End loop.
      endwhile;
   endif;
   
   echo '</div></section>';

}

remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

genesis();