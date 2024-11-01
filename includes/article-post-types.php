<?php
/**
 * Article post types and taxonomies
 */

if ( ! defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly

/**
 * Register article post type
 */
function sfywp_article_post_type() {

    global $sfywp_settings;

    $front_page = ( ! empty ( $sfywp_settings['articles_front_page'] ) ) ? $sfywp_settings['articles_front_page'] : 0;
    $front_page_slug = ( $front_page != 0 ) ? sfywp_get_page_slug( $front_page ) : null;

    $slug = ( ! empty ( $sfywp_settings['articles_slug'] ) ) ? $sfywp_settings['articles_slug'] : 'article';

    define( 'SFYWP_ARTICLES_FRONT_PAGE_SLUG', $front_page_slug );
    define( 'SFYWP_ARTICLES_FRONT_PAGE', intval( $front_page ) );
    define( 'SFYWP_ARTICLES_SLUG', $slug );

    $labels = array(
        'name'                => _x( 'Articles', 'Post Type General Name', 'supportifywp' ),
        'singular_name'       => _x( 'Article', 'Post Type Singular Name', 'supportifywp' ),
        'name_admin_bar'      => __( 'Article', 'supportifywp' ),
        'parent_item_colon'   => __( 'Parent Article:', 'supportifywp' ),
        'all_items'           => __( 'Articles', 'supportifywp' ),
        'add_new_item'        => __( 'Add New Article', 'supportifywp' ),
        'add_new'             => __( 'Add New', 'supportifywp' ),
        'new_item'            => __( 'New Article', 'supportifywp' ),
        'edit_item'           => __( 'Edit Article', 'supportifywp' ),
        'update_item'         => __( 'Update Article', 'supportifywp' ),
        'view_item'           => __( 'View Article', 'supportifywp' ),
        'search_items'        => __( 'Search Article', 'supportifywp' ),
        'not_found'           => __( 'Not found', 'supportifywp' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'supportifywp' ),
    );
    $rewrite = array(
        'slug'                => $slug,
        'with_front'          => false,
        'pages'               => false,
        'feeds'               => true,
    );
    $args = array(
        'label'               => __( 'Article', 'supportifywp' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'author' ),
        'taxonomies'          => array( SFYWP_ARTICLES_CATEGORY_TAX ),
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => false,
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => true,
        'can_export'          => true,
        'has_archive'         => true,
        'hierarchical'        => false,
        'publicly_queryable'  => true,
        'query_var'           => true,
        'exclude_from_search' => false, // TODO: Replacing search
        'capability_type'     => 'page',
        'rewrite'             => $rewrite
    );

    register_post_type( SFYWP_ARTICLES_CPT, $args );
}

add_action( 'init', 'sfywp_article_post_type' );

/**
 * Register article category taxonomy
 */
function sfywp_article_category_taxonomy() {

    global $sfywp_settings;

    //$page = ( !empty ( $sfywp_settings['articles_front_page'] ) ) ? $sfywp_settings['articles_front_page'] : 0;
    //$page = ( !empty ( $sfywp_settings['articles_category_page'] ) ) ? $sfywp_settings['articles_category_page'] : 0;
    $slug = ( !empty ( $sfywp_settings['articles_category_slug'] ) ) ? $sfywp_settings['articles_category_slug'] : 'articles';

    //define( 'SFYWP_ARTICLES_CATEGORY_PAGE', $page );
    define( 'SFYWP_ARTICLES_CATEGORY_SLUG', $slug );

    $labels = array(
        'name'                       => _x( 'Categories', 'Taxonomy General Name', 'supportifywp' ),
        'singular_name'              => _x( 'Category', 'Taxonomy Singular Name', 'supportifywp' ),
        'menu_name'                  => __( 'Categories', 'supportifywp' ),
        'all_items'                  => __( 'All Categories', 'supportifywp' ),
        'parent_item'                => __( 'Parent Category', 'supportifywp' ),
        'parent_item_colon'          => __( 'Parent Category:', 'supportifywp' ),
        'new_item_name'              => __( 'New Category Name', 'supportifywp' ),
        'add_new_item'               => __( 'Add New Category', 'supportifywp' ),
        'edit_item'                  => __( 'Edit Category', 'supportifywp' ),
        'update_item'                => __( 'Update Category', 'supportifywp' ),
        'view_item'                  => __( 'View Category', 'supportifywp' ),
        'separate_items_with_commas' => __( 'Separate categories with commas', 'supportifywp' ),
        'add_or_remove_items'        => __( 'Add or remove categories', 'supportifywp' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'supportifywp' ),
        'popular_items'              => __( 'Popular Categories', 'supportifywp' ),
        'search_items'               => __( 'Search Categories', 'supportifywp' ),
        'not_found'                  => __( 'Not Found', 'supportifywp' ),
    );
    $rewrite = array(
        'slug'                => $slug,
        'with_front'          => false
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_in_menu'               => false,
        'show_in_admin_bar'          => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
        'query_var'                  => true,
        'rewrite'                    => $rewrite,
    );
    register_taxonomy( SFYWP_ARTICLES_CATEGORY_TAX, array( SFYWP_ARTICLES_CPT ), $args );
}

add_action( 'init', 'sfywp_article_category_taxonomy', 0 );