<?php
/**
 * Widgets
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Include widgets
 */
include( SFYWP_PLUGIN_DIR . 'includes/widgets/class.article-category-widget.php' );
include( SFYWP_PLUGIN_DIR . 'includes/widgets/class.article-widget.php' );

/**
 * Register Widgets
 */
function sfywp_register_widgets() {
    register_widget( 'SFYWP_Article_Widget' );
    register_widget( 'SFYWP_Article_Category_Widget' );
}
add_action( 'widgets_init', 'sfywp_register_widgets' );


/**
 * Articles: Get categories
 *
 * @return array
 */
function sfywp_widget_get_article_category_options() {

    $options = array(
        '' => __( 'All categories', 'supportifywp' )
    );

    $terms = sfywp_get_article_categories();

    //sfywp_debug( $terms );

    if ( is_array( $terms ) && sizeof( $terms ) > 0 ) {
        foreach ( $terms as $term ) {
            if ( isset( $term->term_id ) && ! empty( $term->name ) )
                $options[$term->term_id] = $term->name;
        }
    }

    return $options;
}