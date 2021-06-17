<?php

// FPDF generates PDFs
// include( $_SERVER['DOCUMENT_ROOT']. '/wp-content/themes/Genesis-Sandbox-HTML5/fpdf/fpdf.php');

function wf_acf_init() {
acf_update_setting('google_api_key', 'AIzaSyD18dAYjET03oPm82NQiCem8z8VFe92qy0');
}
add_action('acf/init', 'wf_acf_init');


remove_action( 'genesis_site_title', 'genesis_seo_site_title' );
remove_action( 'genesis_site_description', 'genesis_seo_site_description');

genesis_register_sidebar( array(
    'id'              		=> 'header-left',
    'name'         	 	=> __( 'Header Left', 'woodfold_left' ),
    'description'  	=> __( 'Header left widget area', 'woodfold_left' ),
) );

add_action( 'genesis_header', 'wf_left_header_widget', 11 );
	function wf_left_header_widget() {
	if (is_active_sidebar( 'header-left' ) ) {
 	genesis_widget_area( 'header-left', array(
       'before' => '<div class="header-left widget-area">',
       'after'	 => '</div>',
		) );
  }
}

//add bugherd script to header
if ( !is_user_logged_in() ) {
    add_action('wp_head', 'add_head_scripts');
}


function add_head_scripts(){
    ?>

	<script type='text/javascript'>
		(function (d, t) {
		 var bh = d.createElement(t), s = d.getElementsByTagName(t)[0];
		 bh.type = 'text/javascript';
		 bh.src = 'https://www.bugherd.com/sidebarv2.js?apikey=xyty3g5xjk6vlbsuayiaug';
		 s.parentNode.insertBefore(bh, s);
		 })(document, 'script');
	</script>

	<!-- Google Tag Manager -->
	<script>
		(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-5X3GW3D');
	</script>
	<!-- End Google Tag Manager -->

<?php
}

function custom_breadcrumb( $args ) {

	$faq = '<a class="button" href="' . get_field('faq_button_link', 'option') . '" target="_blank"><i class="fa fa-question-circle"></i>FAQ\'S</a>';
	$support = '<a class="button" href="' . get_field('support_button_link', 'option') . '" target="_blank"><i class="fa fa-ticket" aria-hidden="true"></i>Support</a>';
	
	$icons = have_rows('social_icons', 'option');
	
	$args['sep'] = '&raquo;';
	$args['labels']['prefix'] = '';
	//$args['suffix'] = $faq . $support . '</div>';
	
	$icons_html = "";
	if( $icons ):
		$icons_html .= '<div id="bc-social-icons">';
		$i = 1;
		// loop through the rows of data
	    while ( have_rows('social_icons', 'option') ) : the_row();
	    	if( get_row_layout() == 'social_icon' ):
	        
		        $icons_html .= '<div id="icon-' . $i . '" class="bc-icon"><a href="' . get_sub_field('icon_link') . '" style="color:' . get_sub_field('icon_color') . '">' . get_sub_field('icon') . '</a></div>';
	        endif;
		
		$i++;
	    
	    endwhile;
		$icons_html .= '</div>';
	endif;
	
	$args['suffix'] = '<div class="breadcrumb-right">' . $faq . $support . $icons_html . '</div></div>';
	$args['sep'] = '';

  return $args;
}
add_filter( 'genesis_breadcrumb_args', 'custom_breadcrumb' );

remove_action( 'genesis_footer', 'genesis_do_footer' );
genesis_register_sidebar( array(
    'id'              		=> 'footer-top',
    'name'         	 	=> __( 'Footer Top', 'woodfold_footer_top' ),
    'description'  	=> __( 'Footer Top widget area', 'woodfold_footer_top' ),
) );

genesis_register_sidebar( array(
    'id'              		=> 'footer-bottom',
    'name'         	 	=> __( 'Footer Bottom', 'woodfold_footer_bottom' ),
    'description'  	=> __( 'Footer Bottom widget area', 'woodfold_footer_bottom' ),
) );

add_action( 'genesis_footer', 'wf_footer_widget', 11 );
function wf_footer_widget() {
	if (is_active_sidebar( 'footer-top' ) ) {
 	genesis_widget_area( 'footer-top', array(
       'before' => '<div class="footer-top widget-area">',
       'after'	 => '</div>',
		) );
 	}
 	if (is_active_sidebar( 'footer-bottom' ) ) {
 	genesis_widget_area( 'footer-bottom', array(
       'before' => '<div class="footer-bottom widget-area">',
       'after'	 => '</div>',
		) );
  	}
}

add_shortcode('menu', 'menu_function');
function menu_function($atts, $content = null) {
	extract(
		shortcode_atts(
			array( 'name' => null, ),
			$atts
		)
	);
	return wp_nav_menu(
		array(
			'menu' => $name,
			'echo' => false
		)
	);
}

add_filter( 'body_class', 'login_class' );
function login_class( $classes ) {
	$page_template = get_page_template_slug( get_queried_object_id() );

	if( $page_template != 'elevator-form-page.php' || $page_template != 'elevator-dashboard-page.php' ){

		$user_ID = get_current_user_id();
		if ( $user_ID === 0 ) {
			$classes[] = 'not-logged-in';
		} else {
			$classes[] = 'logged-in';
		}
		return $classes;

	}
}

global $current_user; // Use global
get_currentuserinfo(); // Make sure global is set, if not set it.

global $pagenow;
// Check user object has not got subscriber role
if ( user_can( $current_user, "subscriber" ) ) {
	
	if( $pagenow == 'profile.php' ){

		add_action('admin_head', 'admin_color_scheme');
	
		remove_action("admin_color_scheme_picker", "admin_color_scheme_picker");

		add_action('admin_head','hide_personal_options');

		add_action('admin_head', 'localize_ajax');

		add_action('admin_head', 'cart_meta');
	
		add_action('admin_head', 'frontheader');

		add_action( 'admin_enqueue_scripts', 'admin_scripts' );

	}

	show_admin_bar(false);

}

function localize_ajax(){
	$translation_array = array(
		'ajax_url' => admin_url( 'admin-ajax.php')
	);
	
	wp_localize_script( 'cart', 'local', $translation_array );
}

function cart_meta(){
	$user = get_current_user_id();
	$cart_item = random_token(9);
	$secondary_hash = random_token(9);
	echo '<meta property="user-id" content="' . $user . '">';
	echo '<meta property="cart-item" content="' . $cart_item . '">';
	echo '<meta property="secondary-cart-item" content="' . $secondary_hash . '">';
	echo '<meta property="local-ajax" content="' . admin_url('admin-ajax.php') . '">';
}

function frontheader() {

	$header = get_sidebar_profile_content( 'elevator-app-header' );

	echo $header;
	
}

function get_sidebar_profile_content($a){
    ob_start();

    dynamic_sidebar( $a );

    $item = ob_get_contents();

    ob_end_clean();
    
    return $item;

}

function admin_scripts(){
	wp_enqueue_media();
	wp_enqueue_style( 'elevator' );
	wp_enqueue_style( 'bootstrap_css' );
	wp_enqueue_style('roboto', 'https://fonts.googleapis.com/css2?family=Roboto+Condensed&display=swap');
	wp_enqueue_script( 'tether_js', '', null, null, true );
	wp_enqueue_script( 'bootstrap_js');
	wp_enqueue_script( 'cart', '', null, null, true);
	wp_enqueue_script( 'profile', get_stylesheet_directory_uri() . '/js/elevator/profile.js', null, null, true);
}

function admin_color_scheme() {
	global $_wp_admin_css_colors;
	$_wp_admin_css_colors = 0;
}

function hide_personal_options(){
	echo "\n" . 
	'<script type="text/javascript">
	jQuery(document).ready(function($) { 
		$(\'#adminmenumain\').hide();
		$(\'#wpadminbar\').hide();
		$(\'form#your-profile > h3:first\').hide(); 
		$(\'form#your-profile > table:first\').hide(); 
		$(\'form#your-profile\').show();
		$("h2:contains(\'Personal Options\')").hide();
		$("h2:contains(\'Name\')").hide();
		$("h2:contains(\'Contact Info\')").hide();
		$("h2:contains(\'Contact Info\') + table tbody tr[class^=\'user-\'][class$=\'-wrap\']").hide();
		$("tr.user-email-wrap").show();
		$("h2:contains(\'About Yourself\')").hide();
		$("h2:contains(\'About Yourself\') + .form-table").hide();
		$("#your-profile").prepend(\'<h2>Edit Your Profile</h2>\');
		$("#menu-item-1824 a").text("Log Out");
	});
	</script>' . 
	"\n"; 
}

add_action('wp_logout','auto_redirect_external_after_logout');
function auto_redirect_external_after_logout(){
	$page_template = get_page_template_slug( get_queried_object_id() );

   if ( $page_template != 'elevator-dashboard-page.php' ) {
		
		wp_redirect( '/user-dashboard' );
		exit();

   } 

   if ( $page_template != 'elevator-form-page.php' ) {
		
		wp_redirect( '/elevator-form' );
		exit();

	} 
	
	
}

add_action( 'wp_enqueue_scripts', 'add_roboto_fonts' );
function add_roboto_fonts() {
    wp_enqueue_style( 'roboto', 'https://fonts.googleapis.com/css?family=Roboto|Roboto+Condensed:100,300,400,700' );
}

// disallow indexing of post pages
add_action('wp_head', 'no_robots');
function no_robots() {
    if(is_single()){
    	echo "\t<meta name='robots' content='noindex, nofollow' />\r\n";
	}
}

// redirect to home directory
add_action('template_redirect', 'redirect_custom_page');
function redirect_custom_page() {
   if (is_single()) {
   		// TO DO: Send to page specific redirect based on post category;
        wp_safe_redirect(home_url());
        exit();
   }
}

// add the action 
add_action('wp_mail_failed', 'action_wp_mail_failed', 10, 1);
// define the wp_mail_failed callback 
function action_wp_mail_failed($wp_error) 
{
    return error_log(print_r($wp_error, true));
}
          


add_image_size('elevator-form-image', 397, 768, false);
add_image_size('elevator-form-thumb', 9999, 768, false);

function random_token($length){
    if(!isset($length) || intval($length) <= 8 ){
      $length = 32;
    }
    if (function_exists('random_bytes')) {
        return bin2hex(random_bytes($length));
    }
    if (function_exists('mcrypt_create_iv')) {
        return bin2hex(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
    }
    if (function_exists('openssl_random_pseudo_bytes')) {
        return bin2hex(openssl_random_pseudo_bytes($length));
    }
}

function Salt(){
    return substr(strtr(base64_encode(hex2bin(RandomToken(32))), '+', '.'), 0, 44);
}

add_action( 'wp_head', 'special_banner', 25 );
function special_banner(){
   $enabled = get_field('enable_banner', 'option');
   $message = get_field('banner_message', 'option');
   $page_template = get_page_template_slug( get_queried_object_id() );

   if ( $enabled === 'true' && $page_template != 'elevator-form-page.php' && $page_template != 'elevator-dashboard-page.php') {
	   	echo '<div id="special-banner">';
		echo '<p>' . $message . '</p>';
		echo '</div>';
   }

}

add_filter('edit_post_link', 'remove_edit_post_link');
function remove_edit_post_link( $link ) {
	if ( $page_template === 'elevator-form-page.php' && $page_template === 'elevator-dashboard-page.php') {
		return '';
	}
}



// Register new sidebar for left sidebar layout
genesis_register_sidebar( array(
	'id'          => 'left-sidebar',
	'name'        => 'Left Sidebar',
	'description' => 'This is the sidebar for left sidebar template.',
) );


// Register new sidebar for Elevator Page
genesis_register_sidebar( array(
	'id'          => 'elevator-app-header',
	'name'        => 'Elevator App Header',
	'description' => 'This is the header for the Elevator App',
) );

// Register new sidebar for Elevator Page
genesis_register_sidebar( array(
	'id'          => 'elevator-app-footer',
	'name'        => 'Elevator App Footer',
	'description' => 'This is the footer for the Elevator App',
) );

add_action('init', 'register_elevator_scripts');
function register_elevator_scripts() {

	$path = '/js/elevator/';

	wp_register_style( 'elevator', get_stylesheet_directory_uri() . '/css/elevator-app.css' );
	wp_register_script('el-calc', get_stylesheet_directory_uri() . $path . 'calc.js' );
	wp_register_script('cart', get_stylesheet_directory_uri() . $path . 'cart.js' );
	wp_register_script('dash', get_stylesheet_directory_uri() . $path . 'dashboard.js' );
	wp_register_script('el-render', get_stylesheet_directory_uri() . $path . 'render.js' );
	wp_register_script('el-address', get_stylesheet_directory_uri() . $path . 'address.js' );
	wp_register_script('el-struts', get_stylesheet_directory_uri() . $path . 'struts.js' );
	wp_register_script('el-stack', get_stylesheet_directory_uri() . $path . 'stack.js' );
	wp_register_script('el-panels', get_stylesheet_directory_uri() . $path . 'panel-frame.js' );
	wp_register_script('el-review', get_stylesheet_directory_uri() . $path . 'review.js' );
	wp_register_script('el-error', get_stylesheet_directory_uri() . $path . 'error.js' );
	
	wp_register_script('tether_js', 'https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js');
	wp_register_style( 'bootstrap_css', get_stylesheet_directory_uri() . '/css/bootstrap.css' );
	wp_register_script('bootstrap_js', get_stylesheet_directory_uri() . $path . 'bootstrap.min.js');
}

add_filter( 'body_class','elevator_body_classes' );
function elevator_body_classes( $classes ) {
	if ($page_template != 'elevator-form-page.php' && $page_template != 'elevator-dashboard-page.php'){
		
		$classes[] = 'elevator-app';
	
	}

    return $classes;
     
}

add_action('wp_head', 'meta_user_id');
function meta_user_id($user){
	if( is_page( 'elevator-form' ) || is_page('user-dashboard' ) ){
		$user = get_current_user_id();
		$secondary_hash = random_token(9);

		//TEMP OVERRIDE
		//TODO REMOVE BEFORE GOING LIVE
		$user = 8;

		echo '<meta property="user-id" content="' . $user . '">';
		echo '<meta property="secondary-cart-item" content="' . $secondary_hash . '">';
	}
}

add_action( 'wp_ajax_config_retrieval', 'config_retrieval' );
add_action( 'wp_ajax_nopriv_config_retrieval', 'config_retrieval' );

function config_retrieval(){

	$results = config_details( $_POST["data"] );

	if (empty($results)) {
		
		$error = new WP_Error( '007', 'No configuration data was found. Please try again', '' );
 
		wp_send_json_error( $error );

	} else {

		echo $results;

	}
	
	wp_die();
	
}

function config_details( $id ){
	global $wpdb;
	
	$table = $wpdb->prefix . "elevator_form_entries";

	$sql = 'SELECT el_item_data FROM ' . $table .  ' WHERE el_cart_item_id ="' .  $id . '"';
	
	$results = $wpdb->get_results($sql)[0]->el_item_data;

	return $results;
}

add_action( 'wp_ajax_get_cart_contents', 'get_cart_contents' );
add_action( 'wp_ajax_nopriv_get_cart_contents', 'get_cart_contents' );

function get_cart_contents(){

	global $wpdb;
	
	$table = $wpdb->prefix . "elevator_form_entries";
	$user = $_POST["data"];
	
	$sql = 'SELECT el_item_data FROM ' . $table .  ' WHERE status = 0 AND el_user_id = ' . $user;
	
	$results = json_encode($wpdb->get_results($sql));

	echo $results;
	
	wp_die();
	
}

add_action( 'wp_ajax_save_cart_item', 'save_cart_item' );
add_action( 'wp_ajax_nopriv_save_cart_item', 'save_cart_item' );
function save_cart_item(){

	$old_order = $_POST['data']['oldID'];
	$new_order = $_POST['data']['order'];

	$delete = delete($old_order);

	$result = insert($new_order);

	$success = config_details( $new_order['cart_item_id'] );
	
	echo $success;

	wp_die();
}

add_action( 'wp_ajax_insert_order', 'insert_order' );
add_action( 'wp_ajax_nopriv_insert_order', 'insert_order' );
function insert_order(){

	$result = insert($_POST["data"]);
	
	// echo $_POST["data"];
	print_r($result);

	wp_die();

}

function insert($order){

	global $wpdb;

	$wpdb->show_errors();
	$wpdb->suppress_errors = false;
	
	// DEFINE TABLE
	$table = $wpdb->prefix . "elevator_form_entries";

	$exists = create_form_entry_table($wpdb, $table);

	if ( $exists === true ){

		$order = array(
			'created' => current_time('mysql', 1),
			'el_user_id' => $order['user_id'],
			'el_session_id' => $order['session_id'],
			'el_cart_item_id' => $order['cart_item_id'],
			'el_item_data' => json_encode($order, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
			'status' => $order['status'],
		);

		// INSERT NEW ORDER
		$wpdb->insert( $table, $order );

		$result = config_details( $order['el_cart_item_id'] );
		
		return $result;
		
	} else {

		exit( var_dump( $exists ) );

	}
}


add_action( 'wp_ajax_delete_item', 'delete_item' );
add_action( 'wp_ajax_nopriv_delete_item', 'delete_item' );

function delete_item(){

	delete($_POST['data']);

	echo $_POST['data'];
	
	wp_die();
	
}

function delete($id){

	global $wpdb;
	
	$table = $wpdb->prefix . "elevator_form_entries";
		
	$wpdb->delete( $table, array( 'el_cart_item_id' => $id ) );

}

function create_form_entry_table($wpdb, $table) {
	
	$charset_collate = $wpdb->get_charset_collate();
	
	// MAYBE CREATE TABLE
	$sql = "CREATE TABLE IF NOT EXISTS $table (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		created text NOT NULL,
		el_user_id text NOT NULL,
		el_session_id text NOT NULL,
		el_cart_item_id text NOT NULL,
		el_item_data text NOT NULL,
		status TINYINT NOT NULL,
		UNIQUE (id)
	) $charset_collate;";

	// INCLUDE OR ACTION WILL FAIL
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
	dbDelta( $sql );

    $success = empty($wpdb->last_error);

	return $success;
	
}

add_action( 'wp_ajax_update_status', 'update_status' );
add_action( 'wp_ajax_nopriv_update_status', 'update_status' );

function update_status(){

	global $wpdb;
	
	$table = $wpdb->prefix . "elevator_form_entries";
	$data = json_encode($_POST["data"], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

	$sql = 'SELECT id FROM ' . $table .  ' WHERE el_cart_item_id ="' . $_POST["data"]["cart_item_id"] . '"';
	
	$result = $wpdb->get_results($sql);
	$id = $result[0]->id;
	
	$updated = $wpdb->update( $table, array( 'el_item_data' => $data, 'status' => 1 ), array('id'=>$id) );

	// if ( $updated === 1 ){

	// 	send_email($_POST["data"]);

	// }

	echo $updated;

	wp_die();
	
}

add_action( 'wp_ajax_save_form_item', 'save_form_item' );
add_action( 'wp_ajax_nopriv_save_form_item', 'save_form_item' );

function save_form_item(){

	$save = insert($_POST['data']);

	if ( strlen($save) > 0 ){
		echo 1;
	} else {
		echo 0;
	}
	
	wp_die();
}

add_action( 'wp_ajax_check_item_id', 'check_item_id' );
add_action( 'wp_ajax_nopriv_check_item_id', 'check_item_id' );

function check_item_id(){

	global $wpdb;

	$table = $wpdb->prefix . "elevator_form_entries";
		
	$sql = 'SELECT * FROM ' . $table .  ' WHERE el_cart_item_id ="' . $_POST["data"] . '"';

	$result = $wpdb->get_results($sql);


	//print_r($result);

	print_r(count($result));

	wp_die();

}

add_action( 'wp_ajax_edit_cart_item', 'edit_cart_item' );
add_action( 'wp_ajax_nopriv_edit_cart_item', 'edit_cart_item' );

function edit_cart_item(){

	global $wpdb;

	$table = $wpdb->prefix . "elevator_form_entries";
	
	$data = json_encode($_POST["data"]["order"], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	
	$sql = 'SELECT id FROM ' . $table .  ' WHERE el_cart_item_id ="' . $_POST["data"]["id"] . '"';
	
	$result = $wpdb->get_results($sql);

	$updated = $wpdb->update( $table, array( 'el_item_data' => $data, 'status' => 3 ), array('id'=>$result[0]->id) );

	print_r($updated);

	if ( $updated === 1 ){
		echo $updated;
	} else {

		$error = new WP_Error( '012', 'Could not update cart item status. Please check database', '' );
		wp_send_json_error( $error );

	}
	
	wp_die();

}

add_filter( 'wp_mail_from_name', 'wf_sender_name' );
function wf_sender_name( $original_email_from ) {
    return 'Woodfold Manufacturing';
}

add_action( 'wp_ajax_send_email', 'send_email' );
add_action( 'wp_ajax_nopriv_send_email', 'send_email' );
function send_email(){
	
	//TODO: remove get_option(admin_email) before launch 
	// $admin_email = 'woodfold@woodfold.com';
	$admin_email = get_option( 'admin_email' );

	$results = [];
	$results['client_email'] = process_email('client', $_POST['data'], $admin_email);
	$results['admin_email'] = process_email('admin', $_POST['data'], $admin_email);
	$results['order'] = $_POST['data'];
	
	echo json_encode($results);
	
	wp_die();

}

add_action( 'wp_ajax_request_quote_email', 'request_quote_email' );
add_action( 'wp_ajax_nopriv_request_quote_email', 'request_quote_email' );
function request_quote_email(){

	$date_time = new DateTime('NOW');
	$date_formatted = $date_time->format('m/d/Y h:i A');
	$data[0] = $_POST['data'];
	
	$html = email_html_header();
	$html .= email_output_html('admin', $data, $date_formatted, true);
	$html .= '		<!--[if mso]></div><![endif]-->
						<!--[if IE]></div><![endif]-->
						</body>
					</html>';
	$subject = 'Elevator Gate Quote Requested. Date: ' . $date_formatted;
	
	//TODO: remove email overrides and uncomment line below
	//$to = $admin_email;
	$to = 'dawn@orcaservices.net';
	//$to = 'ajstimson@gmail.com';
	$admin_email = get_option( 'admin_email' );

	$headers = email_headers($admin_email, $to);

	$mail_result = false;
	$mail_result = wp_mail( $to, $subject, $html, $headers );

	if ($mail_result === true){
		wp_send_json_success( $mail_result ); 
	} else {
		wp_send_json_error( $mail_result ); 
	}
	wp_die();
}

function process_email($recipient, $data, $admin_email){	

	$orders = [];
	$output = [];
	$i = 0;
	
	foreach ($data as $key => $order) {
		
		$date = $orders[$i]->created;
		
		$orders[$i] = json_decode(config_details($order));
		global $wpdb;
		$table = $wpdb->prefix . "elevator_form_entries";
		$sql = 'SELECT created FROM ' . $table .  ' WHERE el_cart_item_id ="' . $data[$i]  . '"';

		$date_created = $wpdb->get_results($sql);
		$date_time = new DateTime($date_created[0]->created);
		$date_formatted = $date_time->format('m/d/Y h:i A');

		$output[$i]['address_info'] = address_content($orders[$i], $date_formatted);
		$output[$i]['config_details'] = config_content($orders[$i]);

		$i++;

	}

	if ($recipient === 'client'){
		//TODO: remove email overrides and uncomment line below
		//$to = $output[0]['address_info']['shipper_data']['email'];
		$to = 'dawn@orcaservices.net';
		// $to = 'ajstimson@gmail.com';
	}

	if ($recipient === 'admin'){
		//TODO: remove email overrides and uncomment line below
		//$to = $admin_email;
		$to = 'dawn@orcaservices.net';
		//$to = 'ajstimson@gmail.com';
	}

	if ($recipient === 'client'){
		$html = email_output_html('client', $output, $date_formatted, false);
		$subject = 'Your elevator order created on ' . $date_formatted;
	}

	if ($recipient === 'admin'){
		$html = email_html_header();
		$html .= email_output_html('admin', $output, $date_formatted, false);
		$html .= '		<!--[if mso]></div><![endif]-->
						<!--[if IE]></div><![endif]-->
						</body>
					</html>';
		$subject = 'New Elevator Gate Order, created on ' . $date_formatted;
	}

	$headers = email_headers($admin_email, $to);
	
	$mail_result = false;
	$mail_result = wp_mail( $to, $subject, $html, $headers );
	
	return $mail_result;
	
}

function email_output_html($recipient, $output, $date, $request){

	$data = [];
	$i = 0;
	$html = '';

	if ($recipient === 'admin'){
		$html = '<div style="max-width:100%;background-color:#e7e7e7;padding: 50px;">
				<div style="max-width:600px;margin: 0 auto;background-color:#fff;padding: 50px;">';
		if ($request === false){
			$html .= '<p>A new order has been created:</p>';
		}
		if ($request === true){
			$html .= '<p>A quote has been requested:</p>';
		}
	}
	if ($request === false){
		//Grab shipper data outside of loop since it is only repeated later on
		$shipper = $output[0]['address_info']['shipper_data'];
	}

	$html .= '<p align="center" style="background-color: #e7e7e7; padding: 10px;"><b>Date created: ' . $date . '</b></p>';
	
	if ($request === false){
		$html .= '<h3>Account Info</h3>';
		$html .= email_process_html($shipper, 'ul', 'no_title');
	}

	if ($request === true){
		$contact = $output[0]['guest'];
		$html .= '<h3>Contact Info</h3>';
		$html .= email_process_html($contact, 'ul', 'no_title');
	}

	$html .= '<hr>';
	
	if ($request === false){
		foreach ($output as $key => $item) {

			$html .= '<h3>PO Number: ' . $output[$i]['address_info']['po_num']['po'] . '</h3>';
			$html .= '<h3>Sidemark: ' . $output[$i]['address_info']['po_num']['sidemark'] . '</h3>';

			$customer[$i] = $output[$i]['address_info']['customer'];
			
			if (!empty($customer[$i])){
				$customer[$i]['city'] = $customer[$i]['city'] . ', ' . $customer[$i]['state'] . ' ' . $customer[$i]['zip'];
				unset($customer[$i]['state']);
				unset($customer[$i]['zip']);

				$html .= '<p><b>Ship to address: </b></p>';
				$html .= email_process_html($customer[$i], 'ul', 'no_title');
			}


			$html .= '<h3>Configuration Details: </h3>';

			if ($recipient === 'admin'){
				$output[$i]['config_details'] = admin_config_adjustment($output[$i]['config_details']);
			}

			$html .= email_process_html($output[$i]['config_details'], 'table', 'title');

			$html .= '<hr>';

			$i++;
		}
	}

	if ($request === true){

		$html .= '<h3>Configuration Details: </h3>';
		$convert = json_decode(json_encode($output[0]), FALSE);;
		$output[0]['config_details'] = config_content($convert);
		$output[0]['config_details'] = admin_config_adjustment($output[0]['config_details']);

		$html .= email_process_html($output[0]['config_details'], 'table', 'title');
	}

	if ($recipient === 'client'){
		$html = email_html($date, $html);
	}

	if ($recipient === 'admin'){
		$html .= '</div><div>';
	}

	return $html;

}

function admin_config_adjustment($config_details){

	unset($config_details["Quote"]);

	return $config_details;

}

function email_process_html($data, $el, $style){
	
	if ($el === 'ul'){
		$html = '<ul>';
		foreach ($data as $key => $item) {
			$html .= '<li>';

			if ($style === 'title'){
				$html .= '<b>' . $key . ': </b>';
			}

			$html .= $item;
			$html .= '</li>';
		}
		$html .= '</ul>';
	}

	if ($el === 'table'){
		$html = '<table class="config-table"><tbody>';
		$html .= '<colgroup><col span="1" style="width: 40%;"><col span="1" style="width: 60%;"></colgroup>';
		foreach ($data as $key => $item) {
			$html .= '<tr>';

			if ($style === 'title'){
				$html .= '<td><b>' . $key . ':</b></td>';
			}

			$html .= '<td>' . $item . '</td>';
			$html .= '</tr>';
		}
		$html .= '</tbody></table>';
	}

	return $html;
}

function config_table_style(){
	return '
		table.config-table{
			order-collapse: collapse;
			margin: 25px 0;
			max-width: 400px;
			box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
			margin: 0 auto;
		}

		table.config-table tr{
			border: .5px solid #eee;
		}

		table.config-table td{
			padding: 12px 15px;
		}
	';
}

function email_html_header(){
	$html = 
	'<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
			<head>
				<!--[if gte mso 9]>
				<xml>
					<o:OfficeDocumentSettings>
						<o:AllowPNG/>
						<o:PixelsPerInch>96</o:PixelsPerInch>
					</o:OfficeDocumentSettings>
				</xml>
				<![endif]-->
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<meta name="x-apple-disable-message-reformatting">
				<!--[if !mso]><!--><meta http-equiv="X-UA-Compatible" content="IE=edge"><!--<![endif]-->
				<title>
					NEW ORDER: Woodfold Elevator Gate Series 1600
				</title>
			
				<style type="text/css">
					table, td { 
						color: #000000; 
					}
					ul li {
						list-style: none;
					}
					'
					. config_table_style() . 
					'
					@media (max-width: 480px) { 
						#u_content_menu_1 .v-padding { 
							padding: 5px 35px !important; 
						} 
						#u_content_menu_2 .v-padding { 
							padding: 5px 38px 5px 32px !important; 
						} #u_content_menu_4 .v-padding { 
							padding: 5px 40px 5px 50px !important; 
						} #u_content_menu_3 .v-padding { 
							padding: 5px 40px 5px 55px !important; 
						} 
					}
					@media only screen and (min-width: 660px) {
						.u-row {
							width: 640px !important;
						}
						.u-row .u-col {
							vertical-align: top;
						}

						.u-row .u-col-50 {
							width: 320px !important;
						}

						.u-row .u-col-100 {
							width: 640px !important;
						}
					}

					@media (max-width: 660px) {
						.u-row-container {
							max-width: 100% !important;
							padding-left: 0px !important;
							padding-right: 0px !important;
						}
						.u-row .u-col {
							min-width: 320px !important;
							max-width: 100% !important;
							display: block !important;
						}
						.u-row {
							width: calc(100% - 40px) !important;
						}
						.u-col {
							width: 100% !important;
						}
						.u-col > div {
							margin: 0 auto;
						}
					}

					body {
						margin: 0;
						padding: 0;
					}

					table,
					tr,
					td {
						vertical-align: top;
						border-collapse: collapse;
					}

					p {
					margin: 0;
					}

					.ie-container table,
					.mso-container table {
						table-layout: fixed;
					}

					* {
						line-height: inherit;
					}

					a[x-apple-data-detectors="true"] {
						color: inherit !important;
						text-decoration: none !important;
					}

					@media (max-width: 480px) {
						.hide-mobile {
							display: none !important;
							max-height: 0px;
							overflow: hidden;
						}
					}
				</style>
			</head>
			<body class="clean-body" style="margin: 0;padding: 0;-webkit-text-size-adjust: 100%;background-color: #e7e7e7;color: #000000">
				<!--[if IE]><div class="ie-container"><![endif]-->
				<!--[if mso]><div class="mso-container"><![endif]-->';
	return $html;
}

function email_html($date, $html){
	//TODO: replace image location with production site location
	//TODO: replace "tick" image with something on production site
	$html = email_html_header() . 
				'<table style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 320px;Margin: 0 auto;background-color: #e7e7e7;width:100%" cellpadding="0" cellspacing="0">
					<tbody>
						<tr style="vertical-align: top">
							<td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top">
							<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="background-color: #e7e7e7;"><![endif]-->
								<div class="u-row-container" style="padding: 20px 0px 0px;background-color: transparent">
									<div class="u-row" style="Margin: 0 auto;min-width: 320px;max-width: 640px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;">
										<div style="border-collapse: collapse;display: table;width: 100%;background-color: rgb(74, 74, 74);">
										<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 20px 0px 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:640px;"><tr style="background-color: #ffffff;"><![endif]-->	
										<!--[if (mso)|(IE)]><td align="center" width="320" style="width: 320px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
											<div class="u-col u-col-50" style="max-width: 320px;min-width: 320px;display: table-cell;vertical-align: top;">
												<div style="width: 100% !important;">
												<!--[if (!mso)&(!IE)]><!--><div style="padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;"><!--<![endif]-->
								
													<table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
														<tbody>
															<tr>
																<td style="overflow-wrap:break-word;word-break:break-word;padding:20px 20px 15px;font-family:arial,helvetica,sans-serif;" align="left">		
																	<table width="100%" cellpadding="0" cellspacing="0" border="0">
																		<tr>
																			<td style="padding-right: 0px;padding-left: 0px;" align="left">
																				<img align="left" border="0" src="https://woodfolddev.wpengine.com/wp-content/uploads/2020/05/wf-for-dark-background-r.png" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 100%;max-width: 181px;" width="181"/>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
												<!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
												</div>
											</div>
											<!--[if (mso)|(IE)]></td><![endif]-->
											<!--[if (mso)|(IE)]><td align="center" width="320" style="width: 320px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
											<div class="u-col u-col-50" style="max-width: 320px;min-width: 320px;display: table-cell;vertical-align: top;">
												<div style="width: 100% !important;">
												<!--[if (!mso)&(!IE)]><!--><div style="padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;"><!--<![endif]-->
					
													<table class="hide-mobile" style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
														<tbody>
															<tr>
																<td style="overflow-wrap:break-word;word-break:break-word;padding:42px;font-family:arial,helvetica,sans-serif;" align="left">
																	<div style="line-height: 140%; text-align: left; word-wrap: break-word;">
																		<p style="font-size: 14px; line-height: 140%; text-align: right;">
																			<span style="font-size: 16px; line-height: 22.4px; color: #fff;">
																				' . $date . '
																			</span>
																		</p>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
												<!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
												</div>
											</div>
											<!--[if (mso)|(IE)]></td><![endif]-->
											<!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
										</div>
									</div>
								</div>
								<div class="u-row-container" style="padding: 0px;background-color: transparent">
									<div class="u-row" style="Margin: 0 auto;min-width: 320px;max-width: 640px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;">
										<div style="border-collapse: collapse;display: table;width: 100%;background-color: transparent;">
										<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:640px;"><tr style="background-color: #ffffff;"><![endif]-->
										<!--[if (mso)|(IE)]><td align="center" width="640" style="width: 640px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
											<div class="u-col u-col-100" style="max-width: 320px;min-width: 640px;display: table-cell;vertical-align: top;">
												<div style="width: 100% !important;">
												<!--[if (!mso)&(!IE)]><!--><div style="padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;"><!--<![endif]-->
													<table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
														<tbody>
															<tr>
																<td style="overflow-wrap:break-word;word-break:break-word;padding:0px 10px 10px;font-family:arial,helvetica,sans-serif;" align="left">
																	<table height="0px" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;border-top: 3px solid #BBBBBB;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
																		<tbody>
																			<tr style="vertical-align: top">
																				<td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;font-size: 0px;line-height: 0px;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%">
																					<span>&#160;</span>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
													<table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
														<tbody>
															<tr>
																<td style="overflow-wrap:break-word;word-break:break-word;padding:40px 10px 20px;font-family:arial,helvetica,sans-serif;" align="left">
																	<table width="100%" cellpadding="0" cellspacing="0" border="0">
																		<tr>
																			<td style="padding-right: 0px;padding-left: 0px;" align="center">
																				<img align="center" border="0" src="https://img.bayengage.com/assets/1613615720006-tick (1).jpg" alt="Image" title="Image" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: inline-block !important;border: none;height: auto;float: none;width: 100%;max-width: 106px;" width="106"/>
																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
													<table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
														<tbody>
															<tr>
																<td style="overflow-wrap:break-word;word-break:break-word;padding:20px 10px 10px;font-family:arial,helvetica,sans-serif;" align="left">
																	<div style="line-height: 140%; text-align: left; word-wrap: break-word;">
																		<p style="font-size: 14px; line-height: 140%; text-align: center;"><span style="font-size: 18px; line-height: 25.2px; color: #4a4a4a;"><strong>Thank you for placing you order</strong></span><strong style="color: #4a4a4a; font-size: 18px;">!</strong></p>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
													<table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
														<tbody>
															<tr>
																<td style="overflow-wrap:break-word;word-break:break-word;padding:10px 10px 20px;font-family:arial,helvetica,sans-serif;" align="left">       
																	<div style="line-height: 140%; text-align: left; word-wrap: break-word;">
																		<p style="font-size: 14px; line-height: 140%; text-align: center;"><span style="font-size: 12px; line-height: 16.8px;"><strong><span style="color: #4a4a4a; line-height: 16.8px; font-size: 12px;">Please review the details below</span></strong></span></p>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
												<!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
												</div>
											</div>
											<!--[if (mso)|(IE)]></td><![endif]-->
											<!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
										</div>
									</div>
								</div>
								<div class="u-row-container" style="padding: 0px;background-color: transparent">
									<div class="u-row" style="Margin: 0 auto;min-width: 320px;max-width: 640px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;">
										<div style="border-collapse: collapse;display: table;width: 100%;background-color: transparent;">
										<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:640px;"><tr style="background-color: #ffffff;"><![endif]-->
										<!--[if (mso)|(IE)]><td align="center" width="640" style="width: 640px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
											<div class="u-col u-col-100" style="max-width: 320px;min-width: 640px;display: table-cell;vertical-align: top;">
												<div style="width: 100% !important;">
												<!--[if (!mso)&(!IE)]><!--><div style="padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;"><!--<![endif]-->
													<table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
														<tbody>
															<tr>
																<td style="overflow-wrap:break-word;word-break:break-word;padding:10px 10px 20px;font-family:arial,helvetica,sans-serif;" align="left">       
																	<div style="line-height: 140%; text-align: left; word-wrap: break-word;padding-left: 20px;padding-right: 20px;">
																		' . $html . '
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
												<!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
												</div>
											</div>
											<!--[if (mso)|(IE)]></td><![endif]-->
											<!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
										</div>
									</div>
								</div>
								<div class="u-row-container" style="padding: 0px;background-color: transparent">
									<div class="u-row" style="Margin: 0 auto;min-width: 320px;max-width: 640px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;">
										<div style="border-collapse: collapse;display: table;width: 100%;background-color: transparent;">
										<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:640px;"><tr style="background-color: #ffffff;"><![endif]-->
										<!--[if (mso)|(IE)]><td align="center" width="640" style="width: 640px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
											<div class="u-col u-col-100" style="max-width: 320px;min-width: 640px;display: table-cell;vertical-align: top;">
												<div style="width: 100% !important;">
												<!--[if (!mso)&(!IE)]><!--><div style="padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;"><!--<![endif]-->
													<table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
														<tbody>
															<tr>
																<td style="overflow-wrap:break-word;word-break:break-word;padding:10px 10px 20px;font-family:arial,helvetica,sans-serif;" align="left">       
																	<div style="line-height: 140%; text-align: center; word-wrap: break-word;">
																		<p>If you have any questions, please call us at <a href="tel:503-357-7181">503-357-7181</a></p>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
												<!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
												</div>
											</div>
											<!--[if (mso)|(IE)]></td><![endif]-->
											<!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
										</div>
									</div>
								</div>
								<div class="u-row-container" style="padding: 0px;background-color: transparent">
									<div class="u-row" style="Margin: 0 auto;min-width: 320px;max-width: 640px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;">
										<div style="border-collapse: collapse;display: table;width: 100%;background-color:rgb(74,74,74);">
										<!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:640px;"><tr style="background-color: #ffffff;"><![endif]-->
										<!--[if (mso)|(IE)]><td align="center" width="640" style="width: 640px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
											<div class="u-col u-col-100" style="max-width: 320px;min-width: 640px;display: table-cell;vertical-align: top;">
												<div style="width: 100% !important;">
													<table style="font-family:arial,helvetica,sans-serif;" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
														<tbody>
															<tr>
																<td style="overflow-wrap:break-word;word-break:break-word;padding:20px 20px 40px;font-family:arial,helvetica,sans-serif;" align="left">
																	<div style="line-height: 140%; text-align: left; word-wrap: break-word;color: #fff">
																		<p style="color: #fff;font-size: 14px; line-height: 140%; text-align: center;">This email was sent by <span style="color: #E2701E;font-weight:bold;font-size: 14px; line-height: 19.6px;">Woodfold Manufacturing</span>.</p>
																		<p style="color: #fff;font-size: 14px; line-height: 140%; text-align: center;">To ensure delivery to your inbox (not bulk or junk folders), you can add <span style="color: #E2701E; font-size: 14px; line-height: 19.6px;"><a style="color:#fff" href="mailto:support@woodfold.com" target="_blank">support@woodfold.com</a></span> to your address book or safe list.</p>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
												<!--[if (!mso)&(!IE)]><!--></div><!--<![endif]-->
												</div>
											</div>
										<!--[if (mso)|(IE)]></td><![endif]-->
										<!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
										</div>
									</div>
								</div>
							<!--[if (mso)|(IE)]></td></tr></table><![endif]-->
							</td>
						</tr>
					</tbody>
				</table>
				<!--[if mso]></div><![endif]-->
				<!--[if IE]></div><![endif]-->
			</body>
		</html>';
	return $html;
}

function email_headers($from, $to){

	$headers = "From: " . $from . "\r\n";
	$headers .= "Reply-To: ". $to . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	return $headers;
}

function email_config_content($type, $data){

	$array = [];
	$array['customer_shipping'] = get_customer_data($data);

	// $array['json_output'] = json_encode($array);

	$i = 0;
	foreach ($data as $key => $item){
		if ( $key != 'company'
			&& $key != 'first_name'
			&& $key != 'last_name'
			&& $key != 'email'
			&& $key != 'street_address'
			&& $key != 'city'
			&& $key != 'state'
			&& $key != 'zip_code'
			&& $key != 'country'
			&& $key != 'phone'
			&& $key != 'ext'
			&& $key != 'ship_to_country'
			&& $key != 'customer_po_number'
			&& $key != 'customer_sidemark'
			&& $key != 'customer_first_name'
			&& $key != 'customer_last_name'
			&& $key != 'customer_company'
			&& $key != 'ship_to_address'
			&& $key != 'apartment_suite_unit_etc'
			&& $key != 'ship_to_city'
			&& $key != 'state__province__region'
			&& $key != 'ship_to_state'
			&& $key != 'ship_to_region'
			&& $key != 'ship_to_province'
			&& $key != 'zip__postal_code'
			&& $key != 'customer_phone'
			&& $key != 'extension'
		) {
			if( empty($item->type) && $item != '' && !empty($item->value)){
				$array[$key] = $item->value;
			}
			
			if($item->type === 'number' && $item->value !== '0'){
				
				$array[$key] = $item->value;
	
			}
	
			if($item->type === 'text' && $item->value !== ''){
				$array[$key] = $item->value;
			}
	
			if($item->type === 'email' && $item->value !== ''){
				$array[$key] = $item->value;
			}
	
			if($item->type === 'select-one' && $item->value !== '' && $item->value !== 'AA' && $item->value !== 'false'){
				$array[$key] = $item->value;
			}
	
			if($item->type === 'select-one' && $item->value !== '' && $item->value !== 'AA' && $item->value !== 'false'){
				$array[$key] = $item->value;
			}
	
			if( $item->type === 'radio' && !empty($item->value) ){
	
				foreach ($item->value as $nested => $thing){
	
					if( $thing->status === 'true' && $thing->name !== 'No'){
						$array[$key] = $thing->name;
					}
					
				}
			}
		}

		$i++;
	}

	$html = '<hr>';
	$html .= '<h3>PO Number: ' . $array['po_number'] . '</h3>';

	if (!empty($array['sidemark'])){
			$html .= '<h3>Sidemark: ' . $array['sidemark'] . '</h3>';
	}

	unset($array['po_number']);
	unset($array['sidemark']);

	if (!empty($array['customer_shipping'])){
		$html .= '<h3>Ship to Customer</h3>';
		$html .= '<p>';
		foreach ($array['customer_shipping'] as $key => $value){
			$html .= $value . '<br>';
		}
		$html .= '</p>';
	}

	// TODO: create selection for customer shipping (if diff than client)

	$html .= '<p><b>Configuration Details</b></p>';

	$html .= configure_html($array);

	return $html;
	
}

function get_customer_data($data){
	$customer_shipping = [];
	
	if ($data->ship_to_a_different_address->value !== 'false'){
		foreach ($data as $key => $item){
			if ( $key === 'ship_to_country'
				|| $key === 'customer_po_number'
				|| $key === 'customer_sidemark'
				|| $key === 'customer_company'
				|| $key === 'ship_to_address'
				|| $key === 'apartment_suite_unit_etc'
				|| $key === 'ship_to_city'
				|| $key === 'state__province__region'
				|| $key === 'ship_to_state'
				|| $key === 'ship_to_region'
				|| $key === 'ship_to_province'
				|| $key === 'zip__postal_code'
				|| $key === 'customer_phone'
				|| $key === 'extension'
			){
				if (!empty($item->value) && $item->value != 'US'){
	
					$customer_shipping[$key] = $item->value;
	
				}
			}
	
		}
		$name = [];
		if(!empty($data->customer_first_name->value) && !empty($data->customer_last_name->value)){
	
			$name['name'] = $data->customer_first_name->value . ' ' . $data->customer_last_name->value;
	
		}
	
		array_splice( $customer_shipping, 1, 0, $name );
	}
	

	return $customer_shipping;
}

function move_key_position($arr, $find, $move) {
    if (!isset($arr[$find], $arr[$move])) {
        return $arr;
    }

    $elem = [$move=>$arr[$move]];  // cache the element to be moved
    $start = array_splice($arr, 0, array_search($find, array_keys($arr)));
    unset($start[$move]);  // only important if $move is in $start
    return $start + $elem + $arr;
}

function configure_html($array){
	if (!empty($array)){

		$html = '<ul>';
	
		foreach ($array as $key => $value){
			$html .= '<li>' . clean_up_text($key) . ': ' . $value . '</li>';
		}
	
		$html .= '</ul>';
	
		return $html;
		
	}

}

function clean_up_text($str) {
    return ucwords(str_replace("_", " ", $str));
}


add_action( 'wp_ajax_get_salt', 'get_salt' );
add_action( 'wp_ajax_nopriv_get_salt', 'get_salt' );

function get_salt(){

	echo random_token(18);
	
	wp_die();
	
}

add_action( 'wp_ajax_create_pdf', 'create_pdf' );
add_action( 'wp_ajax_nopriv_create_pdf', 'create_pdf' );

function create_pdf(){
	
	$order = json_decode(config_details($_POST['data']));

	global $wpdb;
	$table = $wpdb->prefix . "elevator_form_entries";
	$sql = 'SELECT created FROM ' . $table .  ' WHERE el_cart_item_id ="' . $_POST["data"] . '"';

	$date_created = $wpdb->get_results($sql);
	$date_time = new DateTime($date_created[0]->created);
	$date_formatted = $date_time->format('m/d/Y h:i A');

	
	$output = [];

	if (!empty($order)){
		
		$output['date'] = $date_formatted;
		$output['address_info'] = address_content($order, $output['date']);
		$output['config_details'] = config_content($order);

		$output = json_encode($output);

	} else {

		$output = 'error';

	}

	//print_r($output);
	echo $output;

	wp_die();
}

function address_content($order, $date){
	$array = [];
	$po_num = [];
	$shipper = [];
	$customer =[];

	$po_num['po'] = $order->po_number->value;
	
	if ( !empty($order->sidemark->value) ){

		$po_num['sidemark'] = $order->sidemark->value;
		
	}

	$po_num['date'] = $date;
			
	if ( $order->rush_shipping->value !== 'false' ){

		$po_num['rush'] = 'Yes';

	}

	$array['po_num'] = $po_num;

	$shipper['company'] = $order->company->value;
	$shipper['name'] = $order->first_name->value . ' ' . $order->last_name->value;
	$shipper['email'] = $order->email->value;
	$shipper['street_address'] = $order->street_address->value;

	if ( !empty($order->street_address_2->value) ){

		$shipper['street_address_2'] = $order->street_address_2->value;

	}

	if ( $order->country->value === 'US' ){

		$shipper['street_address_3'] = $order->city->value . ', ' . $order->state->value . ' ' . $order->zip_code->value;

	}

	if ( $order->country->value !== 'US' ){

		$shipper['city'] = $order->city->value;
		$shipper['country'] = $order->country->value;

	}

	if ( !empty( $order->ext->value ) ){

		$shipper['phone'] = $order->phone->value . ' ext: ' . $order->ext->value;

	} else {

		$shipper['phone'] = $order->phone->value;

	}

	$array['shipper_data'] = array_filter($shipper);

	// IF DIFFERENT SHIP TO ADDRESS IS SELECTED

	if ( $order->ship_to_a_different_address->value === 'true' ){

		$customer['po_num'] = $order->customer_po_number->value;
		$customer['sidemark'] = $order->customer_sidemark->value;
		$customer['company'] = $order->customer_company->value;
		$customer['name'] = $order->customer_first_name->value . ' ' . $order->customer_last_name->value;

		if($customer['name']  == ' '){
			unset($customer['name']);
		}
		$customer['street_address'] = $order->ship_to_address->value;

		if ( !empty( $order->apartment_suite_unit_etc->value ) ){

			$customer['street_address_2'] = $order->apartment_suite_unit_etc->value;

		}

		$customer['city'] = $order->ship_to_city->value;

		if ( $order->ship_to_country->value === 'US' ){

			$customer['state'] = $order->ship_to_state->value;
			$customer['zip'] = $order->zip__postal_code->value;

		}

		if ( $order->ship_to_country->value !== 'US' ){

			$customer['country'] = $order->ship_to_country->value;

		}

		$customer['phone'] = $order->customer_phone->value;		

		$array['customer'] = array_filter($customer);

	}

	return $array;

}

function config_content($order){
	$config['Cab Width'] = $order->gate_width->value;

	$pocket_depth = (float)$order->pocket_depth->value;
	//Show pocket depth if greater than 0
	if ($pocket_depth > 0){
		$config['Pocket Depth'] = $order->pocket_depth->value;
	}

	$config['Gate Height'] = $order->cab_height->value;

	$height_options = $order->height_options->value;

	//If height option is not standard - show
	if ($height_options !== '.3125'){
		$config['Height Option'] = $height_options;
	}

	$config['Number of Gate Panels'] = $order->number_of_gate_panels->value;
	$config['Stack Direction'] = radio_sort($order->stack_direction);
	
	$double_gate = radio_sort($order->double_ended_gate);

	//If double gate is selected - show
	if ($double_gate === 'Yes'){
		$config['Double Gate'] = 'Yes';
	}
	
	$config['Panel Material'] = panel_material($order);

	if ($config['Panel Material'] !== 'Custom'){
		$config['Finish'] = panel_finish($order);
	}
	
	$custom_finish = radio_sort($order->finish);
	// Only show special finish if selected
	if ($custom_finish === 'Special Finish'){
		$config['Special Finish'] = $order->enter_type_of_color->value;
	}

	if ($custom_finish === 'Unfinished'){
		$config['Finish Type'] = $custom_finish;
	}

	$vision_panel_status = vision_panel_status($order);
	// Only show vision panel options if selected
	if ( $vision_panel_status === true ){
		$config['Number of Vision Panels'] =$order->number_of_vision_panels->value;
		$config['Vision Panel Position'] = $order->vision_panel_position->value;
		$config['Vision Panel Material'] = $order->vision_panel_material->value;
	}

	$config['Track'] = radio_sort($order->track);
	$config['Hinge Hardware'] = radio_sort($order->hinge_hardware);
	$config['Side Channels'] = radio_sort($order->side_channels);
	$config['Connector Color & Lead Post'] = radio_sort($order->lead_post__connector);

	// Closure options (only show if not standard option)
	for ($i = 0; $i < count($order->closure_options->value); $i++) {
		// $config['Closure Options'][$i] = $order->closure_options->value[$i]->name . ' ' . $order->closure_options->value[$i]->status;
		if (
			$order->closure_options->value[$i]->name !== 'Single Magnetic Catch (Standard)' 
			&& $order->closure_options->value[$i]->status === 'true'
			)
			{
			$config['Closure Options'] = $order->closure_options->value[$i]->name;
		}
	}

	if (!empty($order->order_notes->value)){
		$config['Additional Notes'] = $order->order_notes->value;
	}

	if ($config['Panel Material'] === 'Custom'){
		$config['Quote'] = 'Woodfold will contact you shortly to provide a quote';
	}
	
	return $config;
}

function radio_sort($item){
	$radio = '';

	for ($i = 0; $i < count($item->value); $i++) {
		if ($item->value[$i]->status === 'true'){
			$radio .=  $item->value[$i]->name;
		}
	}

	return $radio;
}

function panel_material($order){
	$panel = '';
	// we need to loop through the following radio options to find what was selected
	
	$panel .= material_sort('Alumifold', $order->acrylic);
	$panel .= material_sort('Hardwood', $order->natural_hardwood_veneers);
	$panel .= material_sort('Vinyl', $order->vinyl_laminate_woodgrains);
	$panel .= material_sort('Vinyl', $order->vinyl_laminate_solid_colors__textures);
	$panel .= material_sort('Alumifold', $order->alumifold_perforated);
	$panel .= material_sort('Alumifold', $order->alumifold_solid);
	$panel .= material_sort('Fire Core', $order->fire_core);
	$panel .= material_sort('Custom', $order->custom);

	return $panel;

}

function material_sort($name, $item){
	$material = '';

	for ($i = 0; $i < count($item->value); $i++) {
		if ($item->value[$i]->status === 'true'){
			$material .=  $name;
		}
	}

	return $material;
}

function panel_finish($order){
	$panel_finish = '';
	
	$panel_finish .= finish_sort($order, $order->natural_hardwood_veneers);
	$panel_finish .= finish_sort($order, $order->vinyl_laminate_woodgrains);
	$panel_finish .= finish_sort($order, $order->vinyl_laminate_solid_colors__textures);
	$panel_finish .= finish_sort($order, $order->acrylic);
	$panel_finish .= finish_sort($order, $order->alumifold_perforated);
	$panel_finish .= finish_sort($order, $order->alumifold_solid);
	$panel_finish .= finish_sort($order, $order->fire_core);
	$panel_finish .= finish_sort($order, $order->custom);

	return $panel_finish;
}

function finish_sort($order, $item){
	$finish = '';

	//Loop through 
	for ($e = 0; $e < count($item->value); $e++) {
		//Find selected panel material finish
		if ($item->value[$e]->status === 'true'){
			$special_finish = '';
			//Then check for special finish
			for ($i = 0; $i < count($order->finish->value); $i++) 
			{
				//If clear finish is not selected
				if ($order->finish->value[$i]->name === 'Clear Finish' && $order->finish->value[$i]->status !== 'true'){
					//Loop through finish and find what was selected
					for ($j = 0; $j < count($order->finish->value); $j++) {
						if ($order->finish->value[$j]->status === 'true' && $order->finish->value[$j]->name !== 'Special Finish'){
							//Store it in a string (finish)
							$special_finish = '(' . $order->finish->value[$j]->name . ')';
						}
						if ($order->finish->value[$j]->status === 'true' && $order->finish->value[$j]->name !== 'Special Finish'){
							//Include Special Finish enter_type_of_color field value
							$special_finish = '(Special Finish: ' . $order->enter_type_of_color->value . ')';
						}
					}
				}
			}
			//Generate object
			$finish .=   $item->value[$e]->name . ' ' . $special_finish;
		}
	}

	return $finish;
}

function vision_panel_status($order){
	$vision_panel = false;
	$vision_data = $order->include_vision_panels;

	for ($i = 0; $i < count($vision_data->value); $i++) {
		if ($vision_data->value[$i]->name === 'True' && $vision_data->value[$i]->status === 'true'){
			$vision_panel = true;
			break;
		}
	}

	return $vision_panel;
}

?>