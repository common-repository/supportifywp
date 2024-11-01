<?php
/**
 * Admin functions
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Check if we are on settings page
 */
function sfywp_is_settings_page() {
    return ( is_admin() && isset( $_GET['page'] ) && 'sfywp_settings' === $_GET['page'] ) ? true : false;
}