<?php
/**
 * Article functions
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Get articles from database
 *
 * @param array $args
 * @return WP_Query
 */
function sfywp_get_articles( $args = array() ) {

    $defaults = array(
        'post_type' => SFYWP_ARTICLES_CPT,
        'post_status' => 'publish',
        'posts_per_page' => -1,
        //'nopaging' => true,
        'orderby' => 'name',
        'order' => 'ASC'
    );

    // Parse args
    $args = wp_parse_args( $args, $defaults );

    // Prepare additional queries
    $meta_queries = array(
        'relation' => 'AND'
    );

    $tax_queries = array(
        'relation' => 'AND'
    );

    //-- Order
    if ( ! empty( $args['sfywp_order'] ) ) {

        $order_options = array( 'ASC', 'DESC' );

        $order = strtoupper( $args['sfywp_order'] );

        if ( in_array( $order, $order_options ) )
            $args['order'] = $order;
    }

    if ( ! empty( $args['sfywp_orderby'] ) ) {

        $orderby = strtolower( $args['sfywp_orderby'] );

        if ( 'name' === $orderby ) {
            $args['orderby'] = 'name';

        } elseif ( 'date' === $orderby ) {
            $args['orderby'] = 'date';

        } elseif ( 'random' === $orderby ) {
            $args['orderby'] = 'rand';

        } elseif ( 'views' === $orderby ) {
            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = SFYWP_POST_VIEWS_META;
        }
    }

    //- Categories
    if ( ! empty( $args['sfywp_article_category'] ) ) {

        $article_categories = explode(',', esc_html( $args['sfywp_article_category'] ) );
        $article_category_tax_field = ( isset( $article_categories[0] ) && is_numeric( $article_categories[0] ) ) ? 'term_taxonomy_id' : 'slug';

        $tax_queries[] = array(
            'taxonomy' => SFYWP_ARTICLES_CATEGORY_TAX,
            'field' => $article_category_tax_field,
            'terms' => $article_categories,
            'operator' => 'IN'
        );
    }

    // Set meta queries
    if ( sizeof( $meta_queries ) > 1 ) {
        $args['meta_query'] = $meta_queries;
    }

    // Set tax queries
    if ( sizeof( $tax_queries ) > 1 ) {
        $args['tax_query'] = $tax_queries;
    }

    //sfywp_debug( $args, 'sfywp_get_articles $args' );

    // The Query
    $articles = new WP_Query( $args );

    // Restore original Post Data
    //wp_reset_postdata();

    // Return
    return $articles;
}

/**
 * Get article categories
 *
 * @param array $args
 * @return array|int|WP_Error
 */
function sfywp_get_article_categories( $args = array() ) {

    $defaults = array(
        'orderby'    => 'name', //'terms_order',
        'order'      => 'ASC',
        'hide_empty' => true,
        'parent'     => 0
    );

    // Parse args
    $args = wp_parse_args( $args, $defaults );

    $terms = get_terms( SFYWP_ARTICLES_CATEGORY_TAX, $args );

    return $terms;
}

/**
 * Get articles front page category items
 */
function sfywp_get_articles_front_page_category_items() {

    global $sfywp_settings;

    return ( ! empty ( $sfywp_settings['articles_front_page_items'] ) ) ? intval( $sfywp_settings['articles_front_page_items'] ) : 5;
}

/**
 * Conditional logic: Articles
 *
 * @return bool
 */
function sfywp_is_articles() {
    return ( sfywp_is_articles_front_page() || sfywp_is_articles_archive() || sfywp_is_articles_singular() ) ? true : false;
}

/**
 * Conditional logic: Articles front page
 *
 * @return bool
 */
function sfywp_is_articles_front_page() {
    return ( get_the_ID() === SFYWP_ARTICLES_FRONT_PAGE || is_post_type_archive( SFYWP_ARTICLES_CPT ) ) ? true : false;
}

/**
 * Conditional logic: Articles archive page
 *
 * @return bool
 */
function sfywp_is_articles_archive() {
    return ( is_tax( SFYWP_ARTICLES_CATEGORY_TAX ) ) ? true : false;
}

/**
 * Conditional logic: Articles singular post
 *
 * @return bool
 */
function sfywp_is_articles_singular() {
    return ( is_singular( SFYWP_ARTICLES_CPT ) ) ? true : false;
}

function sfywp_is_current_article( $post_id ) {

    $queried_post_id = get_queried_object()->ID;

    if ( $post_id === $queried_post_id )
        return true;

    return false;
}

/**
 * Conditional logic: Check if current post is article category (or its ancestors)
 *
 * @param $term_id
 * @param bool $ancestors
 * @return bool
 */
function sfywp_is_current_article_category( $term_id, $ancestors = false ) {

    $queried_term_id = get_queried_object()->term_id;

    if ( ! empty( $queried_term_id ) && $queried_term_id === $term_id )
        return true;

    if ( $ancestors && sfywp_is_articles_singular() && has_term( $term_id, SFYWP_ARTICLES_CATEGORY_TAX ) ) {
        return true;
    }

    return false;
}

/**
 * Get article categories order by options
 *
 * @return array
 */
function sfywp_get_article_categories_orderby_options() {

    $options = array(
        //'' => __( 'Please select...', 'supportifywp' ),
        'name' => __( 'Title', 'supportifywp' ),
        'random' => __( 'Random', 'supportifywp' )
    );

    return $options;
}

/**
 * Get articles order by options
 *
 * @return array
 */
function sfywp_get_articles_orderby_options() {

    $options = array(
        //'' => __( 'Please select...', 'supportifywp' ),
        'name' => __( 'Title', 'supportifywp' ),
        'date' => __( 'Date', 'supportifywp' ),
        'views' => __( 'Views', 'supportifywp' ),
        'random' => __( 'Random', 'supportifywp' )
    );

    return $options;
}

/**
 * Get articles order by default value
 *
 * @return string
 */
function sfywp_get_articles_orderby_default() {
    return 'name';
}

/**
 * Get article icon
 *
 * @return string
 */
function sfywp_get_article_icon() {

    global $sfywp_settings;

    return ( ! empty( $sfywp_settings['articles_icon'] ) ) ? $sfywp_settings['articles_icon'] : '';
}

/**
 * Ordering article archives
 *
 * @param $query
 * @return mixed
 */
function sfywp_article_archive_order( $query ) {

    if ( ! $query->is_main_query() )
        return $query;

    global $sfywp_settings;

    if ( sfywp_is_articles_archive() ) {

        $orderby = ( ! empty( $sfywp_settings['articles_archive_orderby'] ) ) ? $sfywp_settings['articles_archive_orderby'] : sfywp_get_articles_orderby_default();
        $order = ( ! empty( $sfywp_settings['articles_archive_order'] ) ) ? $sfywp_settings['articles_archive_order'] : sfywp_get_order_default();

        $query->set('order', strtoupper( $order ) );

        if ( 'name' === $orderby ) {
            $query->set('orderby', 'name');

        } elseif ( 'date' === $orderby ) {
            $query->set('orderby', 'date');

        } elseif ( 'random' === $orderby ) {
            $query->set('orderby', 'rand');

        } elseif ( 'views' === $orderby ) {
            $query->set('orderby', 'meta_value_num');
            $query->set('meta_key', SFYWP_POST_VIEWS_META );
        }
    }

    return $query;
}
add_filter('pre_get_posts', 'sfywp_article_archive_order' );

