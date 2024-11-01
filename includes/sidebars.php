<?php
/**
 * Sidebars
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Register sidebars
 */
function sfywp_register_sidebars() {

    register_sidebar( array(
        'name' => __( 'Supportifywp: Articles', 'supportifywp' ),
        'id' => 'sfywp-articles',
        'description' => __( 'Widgets in this area will be shown on all article pages if enabled.', 'supportifywp' ),
        'before_widget' => '<aside id="%1$s" class="sfywp-widget widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="sfywp-widget__title">',
        'after_title'   => '</h3>',
    ) );
}

add_action( 'widgets_init', 'sfywp_register_sidebars' );