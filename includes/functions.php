<?php
/**
 * Functions
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Retrieve page slug from post id
 *
 * @param $post_id
 * @return null|string
 */
function sfywp_get_page_slug( $post_id ) {

    $post = get_post( $post_id );

    if ( ! empty( $post->post_name ) ) {
        return $post->post_name;
    }

    return null;
}

/**
 * Get archive title
 *
 * @param null $term_id
 * @return null|string
 */
function sfywp_get_archive_title( $term_id = null ) {

    if ( ! empty( $term_id ) )
        return ''; // Todo

    $obj_name = get_queried_object()->name;

    if ( ! empty( $obj_name ) )
        return $obj_name;

    return null;
}

/**
 * Get archive description
 *
 * @param null $term_id
 * @return string
 */
function sfywp_get_archive_description( $term_id = null ) {

    if ( ! empty( $term_id ) )
        return ''; // Todo

    return get_the_archive_description();
}

/**
 * Default excerpt length
 *
 * @return int
 */
function sfywp_get_default_excerpt_length() {
    return 25;
}

/**
 * Get excerpt based on several conditions
 *
 * @return bool
 */
function sfywp_get_excerpt() {

    if ( post_password_required( get_the_ID() ) )
        return false;

    $excerpt = get_the_excerpt( get_the_ID() );

    return $excerpt;
}

/**
 * Get order options
 *
 * @return array
 */
function sfywp_get_order_options() {

    return array(
        //'' => __( 'Please select...', 'supportifywp' ),
        'asc' => __( 'Ascending ', 'supportifywp' ),
        'desc' => __( 'Descending', 'supportifywp' )
    );
}

/**
 * Get order default value
 *
 * @return string
 */
function sfywp_get_order_default() {
    return 'asc';
}

/**
 * Generating and displaying the breadcrumb
 */
function sfywp_the_breadcrumb() {

    if ( ! defined( 'SFYWP_ARTICLES_FRONT_PAGE' ) || empty( SFYWP_ARTICLES_FRONT_PAGE ) )
        return;

    //-- Defaults
    $front_page_permalink = get_permalink( SFYWP_ARTICLES_FRONT_PAGE );
    $front_page_title = get_the_title( SFYWP_ARTICLES_FRONT_PAGE );

    //-- START: Configuration
    $wrapper_start = '<p id="sfywp-breadcrumb" class="sfywp-breadcrumb">';
    $wrapper_end = '</p>';
    $item_before = '<span class="sfywp-breadcrumb__item">';
    $item_before_active = '<span class="sfywp-breadcrumb__item sfywp-breadcrumb__item--current">';
    $item_after = '</span>';
    $sep = '<span class="sfywp-breadcrumb__sep">&raquo;</span>';

    //-- END: Configuration

    echo $wrapper_start;

    // Front Page only
    if ( get_the_ID() === SFYWP_ARTICLES_FRONT_PAGE ) {
        echo $item_before . '<a href="' . $front_page_permalink . '" title="' . $front_page_title . '">' . $front_page_title . '</a>' . $item_after;
    // Sub pages
    } else {
        // Front Page
        echo $item_before . '<a href="' . $front_page_permalink . '" title="' . $front_page_title . '">' . $front_page_title . '</a>' . $item_after;

        // Taxonomies
        if ( sfywp_is_articles_archive() ) {

            $term = get_queried_object();

            if ( ! empty( $term->term_id ) && ! empty( $term->name ) && ! empty( $term->taxonomy ) ) {
                echo $sep . $item_before_active . $term->name . $item_after;
                //echo $sep . $item_before_active . '<a href="' . get_term_link( $term->term_id ) . '" title="' . $term->name . '">' . $term->name . '</a>' . $item_after;
            }

        // Single post
        } elseif ( sfywp_is_articles_singular() ) {

            $terms = wp_get_post_terms( get_the_ID(), SFYWP_ARTICLES_CATEGORY_TAX, array( 'fields' => 'all' ) );

            //sfywp_debug( $terms );

            if ( ! empty( $terms[0]->term_id ) && ! empty( $terms[0]->name ) && ! empty( $terms[0]->taxonomy ) ) {
                echo $sep . $item_before . '<a href="' . get_term_link( $terms[0]->term_id ) . '" title="' . $terms[0]->name . '">' . $terms[0]->name . '</a>' . $item_after;
            }

            echo $sep . $item_before_active . get_the_title() . $item_after;
        }
    }

    echo $wrapper_end;
}