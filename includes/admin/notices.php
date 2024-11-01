<?php
/**
 * Admin notices
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin notice for updating permalinks
 */
function sfywp_admin_settings_page_notices() {

    if ( ! sfywp_is_settings_page() )
        return;

    if ( ! isset( $_REQUEST['settings-updated'] ) )
        return;

    global $sfywp_settings;

    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e('Settings updated.', 'supportifywp' ) ?></p>
    </div>

    <?php if ( isset( $sfywp_settings['flush_rewrite_rules']) && $sfywp_settings['flush_rewrite_rules'] == '1' ) { ?>
        <div class="notice notice-success is-dismissible">
            <p><?php printf( wp_kses( __( 'Please <a href="%s">save your permalinks</a> in order to use your new slugs.', 'supportifywp' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'options-permalink.php' ) ) ); ?></p>
        </div>
    <?php } ?>

    <?php
}
add_action( 'admin_notices', 'sfywp_admin_settings_page_notices' );