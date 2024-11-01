<?php
/**
 * Admin Menu Pages
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Setup menus
 *
 * Source: http://stackoverflow.com/a/23002306
 */
function sfywp_settings_menu() {

    global $sfywp_menu_slug;

    add_menu_page(
        __( 'SupportifyWP', 'supportifywp' ),
        __( 'SupportifyWP', 'supportifywp' ),
        'edit_pages',
        $sfywp_menu_slug,
        'sfywp_admin_dashboard_page_render',
        'dashicons-sos',
        30
    );

    add_submenu_page(
        $sfywp_menu_slug,
        __( 'Dashboard', 'supportifywp' ),
        __( 'Dashboard', 'supportifywp' ),
        'edit_pages',
        $sfywp_menu_slug,
        'sfywp_admin_dashboard_page_render'
    );

    add_submenu_page(
        $sfywp_menu_slug,
        __( 'All Articles', 'supportifywp' ),
        __( 'Articles', 'supportifywp' ),
        'edit_pages',
        'edit.php?post_type=' . SFYWP_ARTICLES_CPT
    );

    add_submenu_page(
        $sfywp_menu_slug,
        __('All Categories', 'supportifywp'),
        __('Categories', 'supportifywp'),
        'edit_pages',
        'edit-tags.php?taxonomy=' . SFYWP_ARTICLES_CATEGORY_TAX . '&post_type=' . SFYWP_ARTICLES_CPT
    );

    /**
     * Dynamically add more menu items
     */
    do_action( 'sfywp_admin_menu', $sfywp_menu_slug );
}
add_action( 'admin_menu', 'sfywp_settings_menu' );

/**
 * Correct active submenu items for custom post types
 *
 * Source: http://stackoverflow.com/a/23002306
 */
function sfywp_menu_correction( $parent_file ) {

    global $submenu_file, $current_screen, $sfywp_menu_slug;

    if ( $current_screen->post_type == SFYWP_ARTICLES_CPT ) {
        $submenu_file = 'edit.php?post_type=' . SFYWP_ARTICLES_CPT ;
        $parent_file = $sfywp_menu_slug;
    }

    if ( $current_screen->taxonomy == SFYWP_ARTICLES_CATEGORY_TAX ) {
        $submenu_file = 'edit-tags.php?taxonomy=' . SFYWP_ARTICLES_CATEGORY_TAX . '&post_type=' . SFYWP_ARTICLES_CPT;
        $parent_file = $sfywp_menu_slug;
    }

    return $parent_file;
}
add_action('parent_file', 'sfywp_menu_correction');
