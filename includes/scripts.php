<?php
/**
 * Scripts
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Load admin scripts
 *
 * @since       1.0.0
 * @global      string $post_type The type of post that we are editing
 * @return      void
 */
function sfywp_admin_scripts( $hook ) {

    // Use minified libraries if SCRIPT_DEBUG is turned off
    $suffix = ( ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ) ? '' : '.min';

    /**
     *	Settings page only
     */
    $screen = get_current_screen();

    if ( ! empty( $screen->base ) && ( strpos( $screen->base, 'sfywp') !== false || $screen->base == 'widgets' ) ) {

        wp_enqueue_script( 'sfywp-admin-script', SFYWP_PLUGIN_URL . 'public/js/admin' . $suffix . '.js', array( 'jquery' ), SFYWP_PLUGIN_VER );
        wp_enqueue_style( 'sfywp-admin-style', SFYWP_PLUGIN_URL . 'public/css/admin' . $suffix . '.css', false, SFYWP_PLUGIN_VER );
    }
}
add_action( 'admin_enqueue_scripts', 'sfywp_admin_scripts', 100 );

/**
 * Load frontend scripts
 *
 * @since       1.0.0
 * @return      void
 */
function sfywp_scripts( $hook ) {

    // Use minified libraries if SCRIPT_DEBUG is turned off
    $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    wp_enqueue_script( 'sfywp-script', SFYWP_PLUGIN_URL . 'public/js/scripts' . $suffix . '.js', array( 'jquery' ), SFYWP_PLUGIN_VER, true );
    wp_enqueue_style( 'sfywp-style', SFYWP_PLUGIN_URL . 'public/css/styles' . $suffix . '.css', false, SFYWP_PLUGIN_VER );
}
add_action( 'wp_enqueue_scripts', 'sfywp_scripts' );