<?php
/**
 * Template functions
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Initialize session
 */
function sfywp_session_init() {
    if(!session_id()) {
        session_start();
    }
}
//add_action( 'init', 'sfywp_session_init', 1 );

/**
 * Destroy session
 */
function sfywp_session_destroy() {
    session_destroy();
}
//add_action( 'wp_logout', 'sfywp_session_destroy' );

/**
 * Optimizing excerpt length for our posts
 *
 * @param $length
 * @return int
 */
function sfywp_excerpt_length( $length ) {

    global $post;

    if ( SFYWP_ARTICLES_CPT === get_post_type( $post ) ) {
        return sfywp_get_default_excerpt_length();
    }

    return $length;
}
add_filter( 'excerpt_length', 'sfywp_excerpt_length', 999 );

/**
 * Optimizing excerpt more for our posts
 *
 * @param $more
 * @return string
 */
function sfywp_excerpt_more( $more ) {

    global $post;

    if ( SFYWP_ARTICLES_CPT === get_post_type( $post ) ) {
        return apply_filters( 'sfywp_excerpt_more', '&hellip;' );
    }

    return $more;
}
add_filter('excerpt_more', 'sfywp_excerpt_more', 999 );

/**
 * Custom CSS
 */
function sfywp_insert_custom_css() {

    global $sfywp_settings;

    $custom_css_activated = ( isset( $sfywp_settings['custom_css_activated'] ) && $sfywp_settings['custom_css_activated'] == '1' ) ? true : false;

    if ( $custom_css_activated && ! empty ( $sfywp_settings['custom_css'] ) ) {
        echo '<style type="text/css">' . $sfywp_settings['custom_css'] . '</style>';
    }
}
add_action( 'wp_head','sfywp_insert_custom_css' );

/**
 * Replacing WordPress 'get_the_archive_title'
 *
 * @param $title
 * @return null|string
 */
function sfywp_replace_get_the_archive_title( $title ) {

    if ( sfywp_is_articles_archive() ) {
        $archive_title = sfywp_get_archive_title();

        if ( ! empty( $archive_title ) )
            return $archive_title;
    }

    return $title;
}
add_filter('get_the_archive_title', 'sfywp_replace_get_the_archive_title' );