<?php
    /**
    * Template Name: Elevator success
    */




    add_action('wp_head', 'meta_user_id');
    function meta_user_id(){

        echo '<meta property="user-id" content="' . get_current_user_id()
        . '">';
    }

    global $header;
    // global $footer;

    $header = get_sidebar_content( 'elevator-app-header' );
    // $footer = get_sidebar_content( 'elevator-app-footer' );
    
    function get_sidebar_content($a){
        ob_start();
    
        dynamic_sidebar( $a );
    
        $item = ob_get_contents();
    
        ob_end_clean();
        
        return $item;
    
    }




add_action( 'wp_enqueue_scripts', 'app_scripts' );
function app_scripts() {
	wp_enqueue_style( 'elevator' );
	wp_enqueue_script( 'tether_js' );
	wp_enqueue_script( 'bootstrap_js' );
    wp_enqueue_script( 'cart' );
    wp_enqueue_script( 'dash' );
}
    
    remove_post_type_support( 'page', 'genesis-scripts' );
    
    remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
    //* Remove site header elements
    remove_action( 'genesis_header', 'genesis_header_markup_open', 5 );
    remove_action( 'genesis_header', 'genesis_do_header' );
    remove_action( 'genesis_header', 'genesis_header_markup_close', 15 );
    remove_action( 'genesis_meta', 'genesis_load_stylesheet' );
    
    //* Remove navigation
    remove_theme_support( 'genesis-menus' );
    
    //* Remove breadcrumbs
    remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
    
    remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
    
    //* Remove site footer elements
    remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
    remove_action( 'genesis_footer', 'genesis_do_footer' );
    remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );
    
    // explicitly deactivate uneeded plugins
    deactivate_plugins( 
        array( 	
            '/genesis-simple-sidebars/plugin.php',
            '/mobile-menu/mobmenu.php',
            '/php-everywhere/phpeverywhere.php',
            '/revslider/revslider.php',
            '/svg-support/svg-support.php',
            '/wordpress-seo/index.php',
        ) 
    );
    
    // remove all widgets
    add_filter( 'sidebars_widgets', 'disable_all_widgets' );
    function disable_all_widgets( $sidebars_widgets ) {
        $sidebars_widgets = array( false ); 
        return $sidebars_widgets;
    }

    // Now Add Stuff!

add_action( 'genesis_header', 'header_content', 12 );
function header_content($header){
	echo '<header>';
	global $header;
	echo $header;
	echo '</header>';
}

add_action( 'genesis_entry_content', 'do_content', 9 );
function do_content() {
	echo '<article>';
	dashboard_content();
	echo '</article>';
}

// add_action( 'genesis_footer', 'footer_content', 12 );
// function footer_content($footer){
// 	echo '<footer><div class="footer-wrap">';
// 	global $footer;
// 	echo $footer;
// 	echo '</div></footer>';
// }

function dashboard_content(){
    global $wpdb;
     
echo '<p>Success! You will receive a confirmation email shortly</p>';
echo '<p>Check you orders <a href="https://woodfoldstage.wpengine.com/user-dashboard/">Click Here</a></p>';
}


    genesis();