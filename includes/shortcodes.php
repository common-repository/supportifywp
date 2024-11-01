<?php
/**
 * Shortcodes
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

function sfywp_articles_shortcode( $atts, $content ) {

    ob_start();



    $str = ob_get_clean();

    return $str;
}
//add_shortcode( '', 'sfywp_articles_shortcode' );