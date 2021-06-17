<?php

/*
* File: Generates PDF
* Dependencies: /fpdf/fpdf.php.
* Version: 1.0
* Author: Andrew Stimson
* Author URI: https://applejuice.codes
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

include( '/fpdf/fpdf.php');



add_action( 'wp_ajax_create_pdf', 'create_pdf' );
add_action( 'wp_ajax_nopriv_create_pdf', 'create_pdf' );

function create_pdf(){
    
    $pdf = new PDF_HTML();

    print_r($pdf);
}