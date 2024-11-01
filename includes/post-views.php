<?php
/**
 * Post Views
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Update post views on page visit
 */
function sfywp_update_post_views() {

    if ( sfywp_user_is_admin() )
        return;

    if ( ! sfywp_is_articles_singular() )
        return;

    global $post;

    $post_id = ( isset( $post->ID ) ) ? $post->ID : null;

    if ( ! empty( $post_id ) ) {

        /*
        // Prevent view count hijacking
        $session_post_views = ( isset( $_SESSION['sfywp_post_views'] ) ) ? $_SESSION['sfywp_post_views'] : array();

        if( is_array( $session_post_views ) && in_array( $post_id, $session_post_views ) )
            return;

        // Store recently visited post in session
        $session_post_views[] = $post_id;
        $_SESSION['sfywp_post_views'] = $session_post_views;
        */

        sfywp_increment_post_views( $post_id );
    }

}
add_action( 'wp_head','sfywp_update_post_views' );

/**
 * Maybe displaying the post views
 */
function sfywp_the_post_views() {

    $views = sfywp_get_post_views( get_the_ID() );

    if ( ! empty( $views ) )
        printf( esc_html( _n( '%d view', '%d views', $views, 'supportifywp'  ) ), $views );
}

/**
 * Get post views
 *
 * @param $post_id
 * @return int|mixed
 */
function sfywp_get_post_views( $post_id ) {

    if ( empty( $post_id ) )
        return 0;

    $views = get_post_meta( $post_id, SFYWP_POST_VIEWS_META, true );

    return ( ! empty( $views ) ) ? $views : 0;
}

/**
 * Increment post views
 *
 * @param $post_id
 */
function sfywp_increment_post_views( $post_id ) {

    if ( empty( $post_id ) )
        return;

    $views = get_post_meta( $post_id, SFYWP_POST_VIEWS_META, true );

    if ( $views == '' ) {
        delete_post_meta( $post_id, SFYWP_POST_VIEWS_META );
        add_post_meta( $post_id, SFYWP_POST_VIEWS_META, 1 );
    } else {
        $views++;
        update_post_meta( $post_id, SFYWP_POST_VIEWS_META, $views );
    }
}

/**
 * Set initial post view value
 *
 * @param $post_id
 */
function sfywp_init_post_views( $post_id ) {

    $views = get_post_meta( $post_id, SFYWP_POST_VIEWS_META, true );

    if ( '' == $views ) {
        delete_post_meta( $post_id, SFYWP_POST_VIEWS_META );
        add_post_meta( $post_id, SFYWP_POST_VIEWS_META, 0 );
    }
}
add_action( 'publish_sfywp_article', 'sfywp_init_post_views', 10, 1 );