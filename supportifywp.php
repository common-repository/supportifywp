<?php
/*
Plugin Name: SupportifyWP
Plugin URL: http://supportifywp.com/
Description: Create a powerful and customizable documentation or knowledgebase for your business or product.
Version: 1.0.1
Author: flowdee
Author URI: http://flowdee.de
Contributors: flowdee
Text Domain: supportifywp
Domain Path: languages
*/

/*******************************************
 * global constants
 *******************************************/

if ( !defined( 'SFYWP_PLUGIN_VER' ) ) {
    define( 'SFYWP_PLUGIN_VER', '1.0.1' );
}

if ( !defined( 'SFYWP_PLUGIN_DIR' ) ) {
    define( 'SFYWP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( !defined( 'SFYWP_PLUGIN_URL' ) ) {
    define( 'SFYWP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( !defined( 'SFYWP_PLUGIN_FILE' ) ) {
    define( 'SFYWP_PLUGIN_FILE', __FILE__ );
}
if ( !defined( 'SFYWP_TEMPLATES_DIR' ) ) {
    define( 'SFYWP_TEMPLATES_DIR', dirname( __FILE__ ) . '/templates/' );
}

/*******************************************
 * global variables
 *******************************************/
global $wpdb;

$sfywp_settings = get_option( 'sfywp_settings', array() );
$sfywp_menu_slug = 'syfwp-dashboard';

/*******************************************
 * other constants
 *******************************************/

if ( !defined( 'SFYWP_ARTICLES_CPT' ) ) {
    define( 'SFYWP_ARTICLES_CPT', 'sfywp_article' );
}

if ( !defined( 'SFYWP_ARTICLES_CATEGORY_TAX' ) ) {
    define( 'SFYWP_ARTICLES_CATEGORY_TAX', 'sfywp_category' );
}

if ( !defined( 'SFYWP_POST_VIEWS_META' ) ) {
    define( 'SFYWP_POST_VIEWS_META', '_sfywp_post_views' );
}

/*******************************************
 * plugin text domain for translations
 *******************************************/
function sfywp_load_textdomain() {
    load_plugin_textdomain( 'supportifywp', false, dirname( plugin_basename( SFYWP_PLUGIN_FILE ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'sfywp_load_textdomain' );

/*******************************************
 * plugin admin meta
 *******************************************/
function sfywp_plugin_row_meta( $input, $file ) {
    if ( $file != 'supportifywp/supportifywp.php' )
        return $input;

    $links = array(
        '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=sfywp_settings') ) .'">' . __('Settings', 'supportifywp') . '</a>'
    );

    $input = array_merge( $input, $links );

    return $input;
}

add_filter( 'plugin_row_meta', 'sfywp_plugin_row_meta', 10, 2 );

/*******************************************
 * file includes
 *******************************************/

// Global includes
include( SFYWP_PLUGIN_DIR . 'includes/helper.php' );
include( SFYWP_PLUGIN_DIR . 'includes/functions.php' );
include( SFYWP_PLUGIN_DIR . 'includes/scripts.php' );
include( SFYWP_PLUGIN_DIR . 'includes/article-functions.php' );
include( SFYWP_PLUGIN_DIR . 'includes/article-post-types.php' );
include( SFYWP_PLUGIN_DIR . 'includes/sidebars.php' );
include( SFYWP_PLUGIN_DIR . 'includes/template-functions.php' );
include( SFYWP_PLUGIN_DIR . 'includes/hooks.php' );
include( SFYWP_PLUGIN_DIR . 'includes/widgets.php' );
include( SFYWP_PLUGIN_DIR . 'includes/post-views.php' );

// Admin only includes
if( is_admin() ) {
    include( SFYWP_PLUGIN_DIR . 'includes/admin/functions.php' );
    include( SFYWP_PLUGIN_DIR . 'includes/admin/pages.php' );
    include( SFYWP_PLUGIN_DIR . 'includes/admin/notices.php' );
    include( SFYWP_PLUGIN_DIR . 'includes/admin/class.dashboard.php' );
    include( SFYWP_PLUGIN_DIR . 'includes/admin/class.settings.php' );
}