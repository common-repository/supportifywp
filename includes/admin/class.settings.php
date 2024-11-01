<?php
/**
 * Admin settings page
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if ( ! class_exists('SFYWP_Settings' ) ) {

    class SFYWP_Settings {

        /**
         * Construct the plugin object
         */
        public function __construct()
        {
            global $sfywp_settings;

            // Options
            $this->settings = $sfywp_settings;

            // Init menu and settings
            add_action( 'sfywp_admin_menu', array( &$this, 'add_admin_menu'), 90 );
            add_action( 'admin_init', array( &$this, 'init_settings' ) );
        }

        /**
         * Add main menu
         *
         * @param $parent_menu_slug
         */
        public function add_admin_menu( $parent_menu_slug )
        {

            add_submenu_page(
                $parent_menu_slug,
                __( 'Settings', 'supportifywp' ),
                __( 'Settings', 'supportifywp' ),
                'edit_pages',
                'sfywp_settings',
                array( &$this, 'render_settings_page' )
            );
        }

        function init_settings()
        {

            // Register options
            register_setting(
                'sfywp_settings',
                'sfywp_settings',
                array( &$this, 'validate_input_callback' )
            );

            // Section: Quickstart
            add_settings_section(
                'sfywp_section_quickstart',
                __('Quickstart guide', 'supportifywp'),
                false, //array( &$this, 'section_quickstart_render' ),
                'sfywp_settings'
            );

            // Section: Articles settings
            add_settings_section(
                'sfywp_articles_section',
                __('Articles', 'supportifywp'),
                false,
                'sfywp_settings'
            );

            add_settings_field(
                'sfywp_articles_front_page',
                __('Front page', 'supportifywp'),
                array(&$this, 'articles_front_page_render'),
                'sfywp_settings',
                'sfywp_articles_section',
                array('label_for' => 'sfywp_articles_front_page')
            );

            add_settings_field(
                'sfywp_articles_archive',
                __('Archives', 'supportifywp'),
                array(&$this, 'articles_archive_render'),
                'sfywp_settings',
                'sfywp_articles_section'
            );

            add_settings_field(
                'sfywp_articles_single',
                __('Single articles', 'supportifywp'),
                array(&$this, 'articles_single_render'),
                'sfywp_settings',
                'sfywp_articles_section'
            );

            add_settings_field(
                'sfywp_articles_other',
                __('Other', 'supportifywp'),
                array(&$this, 'articles_other_render'),
                'sfywp_settings',
                'sfywp_articles_section'
            );

            // Section: General settings
            add_settings_section(
                'sfywp_general_section',
                __('General', 'supportifywp'),
                false,
                'sfywp_settings'
            );

            add_settings_field(
                'sfywp_custom_css',
                __('Custom CSS', 'supportifywp'),
                array(&$this, 'custom_css_render'),
                'sfywp_settings',
                'sfywp_general_section',
                array('label_for' => 'sfywp_custom_css')
            );

            add_settings_field(
                'sfywp_custom_content_wrapper',
                __('Custom HTML Wrapper', 'supportifywp'),
                array(&$this, 'custom_content_wrapper_render'),
                'sfywp_settings',
                'sfywp_general_section',
                array('label_for' => 'sfywp_custom_content_wrapper')
            );
            
        }

        /**
         * Validate input callback
         * 
         * @param $input
         * @return mixed
         */
        function validate_input_callback( $input ) {

            // Prevent flushing rewrite rules by default
            $flush_rewrite_rules = false;

            // Single slug
            if ( empty ( $input['articles_slug'] ) )
                $input['articles_slug'] = SFYWP_ARTICLES_SLUG;

            if ( ! isset( $this->settings['articles_slug'] ) )
                $flush_rewrite_rules = true;

            if ( isset( $this->settings['articles_slug'] ) && $this->settings['articles_slug'] != $input['articles_slug'] )
                $flush_rewrite_rules = true;

            // Category slug
            if ( empty ( $input['articles_category_slug'] ) )
                $input['articles_category_slug'] = SFYWP_ARTICLES_CATEGORY_SLUG;

            if ( ! isset( $this->settings['articles_category_slug'] ) )
                $flush_rewrite_rules = true;

            if ( isset( $this->settings['articles_category_slug'] ) && $this->settings['articles_category_slug'] != $input['articles_category_slug'] )
                $flush_rewrite_rules = true;

            // Flush rewrite rules if required
            if ( $flush_rewrite_rules )
                $input['flush_rewrite_rules'] = 1; // flush_rewrite_rules(); // TODO

            return $input;
        }

        /**
         * Section "quickstart" render
         */
        function section_quickstart_render() {
            ?>

            <div class="postbox">
                <h3 class='hndle'><?php _e('Quickstart Guide', 'supportifywp'); ?></h3>
                <div class="inside">
                    <?php do_action( 'sfywp_settings_quickstart_render' ); ?>
                </div>
            </div>

            <?php
        }

        /**
         * Articles: Front page render
         */
        function articles_front_page_render() {

            $pages = get_pages();

            $front_page = ( ! empty ( $this->settings['articles_front_page'] ) ) ? $this->settings['articles_front_page'] : SFYWP_ARTICLES_FRONT_PAGE;
            $items = ( ! empty ( $this->settings['articles_front_page_items'] ) ) ? $this->settings['articles_front_page_items'] : 5;

            $orderby_options = sfywp_get_articles_orderby_options();
            $orderby = ( ! empty( $this->settings['articles_front_page_orderby'] ) ) ? $this->settings['articles_front_page_orderby'] : sfywp_get_articles_orderby_default();
            $order_options = sfywp_get_order_options();
            $order = ( ! empty( $this->settings['articles_front_page_order'] ) ) ? $this->settings['articles_front_page_order'] : sfywp_get_order_default();

            $excerpt = ( isset ( $this->settings['articles_front_page_excerpt'] ) && $this->settings['articles_front_page_excerpt'] == '1' ) ? 1 : 0;

            ?>
            <h4><?php _e('Page', 'supportifywp' ); ?></h4>
            <p>
                <select id="sfywp_articles_front_page" name="sfywp_settings[articles_front_page]">
                    <option value="0"><?php _e('Please select...', 'supportifywp'); ?></option>
                    <?php foreach ( $pages as $page ) { ?>
                        <option value="<?php echo $page->ID; ?>" <?php selected( $front_page, $page->ID ); ?>><?php echo $page->post_title; ?></option>
                    <?php } ?>
                </select>&nbsp;
                <?php if ( $front_page != 0 ) { ?>
                    <small><?php printf( __( 'Front page url will be: %1$s', 'supportifywp' ), get_permalink( $front_page ) ); ?></small>
                <?php } ?>
            </p>

            <h4><?php _e('Articles per category', 'supportifywp' ); ?></h4>
            <p>
                <input id="sfywp_articles_front_page_items" type="number" name="sfywp_settings[articles_front_page_items]" value="<?php echo $items; ?>">&nbsp;
                <small><?php _e( 'Maximum amount of articles to be shown inside a category.', 'supportifywp' ); ?></small>
            </p>

            <h4><?php _e('Order articles by', 'supportifywp' ); ?></h4>
            <p>
                <select id="sfywp_articles_front_page_orderby" name="sfywp_settings[articles_front_page_orderby]">
                    <?php foreach ( $orderby_options as $key => $label ) { ?>
                        <option value="<?php echo $key; ?>" <?php selected( $orderby, $key ); ?>><?php echo $label; ?></option>
                    <?php } ?>
                </select>
                &nbsp;
                <select id="sfywp_articles_front_page_order" name="sfywp_settings[articles_front_page_order]">
                    <?php foreach ( $order_options as $key => $label ) { ?>
                        <option value="<?php echo $key; ?>" <?php selected( $order, $key ); ?>><?php echo $label; ?></option>
                    <?php } ?>
                </select>
            </p>

            <h4><?php _e('Excerpt', 'supportifywp' ); ?></h4>
            <p>
                <input type="checkbox" id="sfywp_articles_front_page_excerpt" name="sfywp_settings[articles_front_page_excerpt]" value="1" <?php echo( $excerpt == 1 ? 'checked' : '' ); ?> />
                <label for="sfywp_articles_front_page_excerpt"><?php _e('Show article excerpt on front page page', 'supportifywp'); ?></label>
            </p>
            <?php
        }

        /**
         * Articles: Archives
         */
        function articles_archive_render() {

            $category_slug = ( ! empty ( $this->settings['articles_category_slug'] ) ) ? $this->settings['articles_category_slug'] : SFYWP_ARTICLES_CATEGORY_SLUG;
            $hide_archive_description = ( isset ( $this->settings['articles_hide_archive_description'] ) && $this->settings['articles_hide_archive_description'] == '1' ) ? 1 : 0;

            $orderby_options = sfywp_get_articles_orderby_options();
            $orderby = ( ! empty( $this->settings['articles_archive_orderby'] ) ) ? $this->settings['articles_archive_orderby'] : sfywp_get_articles_orderby_default();
            $order_options = sfywp_get_order_options();
            $order = ( ! empty( $this->settings['articles_archive_order'] ) ) ? $this->settings['articles_archive_order'] : sfywp_get_order_default();

            $excerpt = ( isset ( $this->settings['articles_archives_excerpt'] ) && $this->settings['articles_archives_excerpt'] == '1' ) ? 1 : 0;

            ?>
            <h4><?php _e('Permalinks', 'supportifywp' ); ?></h4>
            <p>
                <input id="sfywp_articles_category_slug" type="text" name="sfywp_settings[articles_category_slug]" value="<?php echo $category_slug; ?>" placeholder="articles">
            </p>
            <p>
                <small><?php printf( __( 'Category url will be like: %1$s', 'supportifywp' ), esc_url( home_url() ) . '/<strong>' . $category_slug . '</strong>/category-name/' ); ?></small>
            </p>

            <h4><?php _e('Hide archive description', 'supportifywp' ); ?></h4>
            <p>
                <input type="checkbox" id="sfywp_articles_hide_archive_description" name="sfywp_settings[articles_hide_archive_description]" value="1" <?php echo( $hide_archive_description == 1 ? 'checked' : '' ); ?> />
                <label for="sfywp_articles_hide_archive_description"><?php _e('Check in order to hide the output of the archive description', 'supportifywp'); ?></label>
                <br />
                <small><?php _e( 'This option only applies to the output made by this plugin.', 'supportifywp' ); ?></small>
            </p>

            <h4><?php _e('Order articles by', 'supportifywp' ); ?></h4>
            <p>
                <select id="sfywp_articles_archive_orderby" name="sfywp_settings[articles_archive_orderby]">
                    <?php foreach ( $orderby_options as $key => $label ) { ?>
                        <option value="<?php echo $key; ?>" <?php selected( $orderby, $key ); ?>><?php echo $label; ?></option>
                    <?php } ?>
                </select>
                &nbsp;
                <select id="sfywp_articles_archive_order" name="sfywp_settings[articles_archive_order]">
                    <?php foreach ( $order_options as $key => $label ) { ?>
                        <option value="<?php echo $key; ?>" <?php selected( $order, $key ); ?>><?php echo $label; ?></option>
                    <?php } ?>
                </select>
            </p>

            <h4><?php _e('Excerpt', 'supportifywp' ); ?></h4>
            <p>
                <input type="checkbox" id="sfywp_articles_archives_excerpt" name="sfywp_settings[articles_archives_excerpt]" value="1" <?php echo( $excerpt == 1 ? 'checked' : '' ); ?> />
                <label for="sfywp_articles_archives_excerpt"><?php _e('Show article excerpt in category archives', 'supportifywp'); ?></label>
            </p>
            <?php
        }

        /**
         * Articles: Single articles
         */
        function articles_single_render() {

            $article_slug = ( ! empty ( $this->settings['articles_slug'] ) ) ? $this->settings['articles_slug'] : SFYWP_ARTICLES_SLUG;

            ?>
            <h4><?php _e('Permalinks', 'supportifywp' ); ?></h4>
            <p>
                <input id="sfywp_articles_slug" type="text" name="sfywp_settings[articles_slug]" value="<?php echo $article_slug; ?>" placeholder="article">
            </p>
            <p><small><?php printf( __( 'Single article url will be like: %1$s', 'supportifywp' ), esc_url( home_url() ) . '/<strong>' . $article_slug . '</strong>/title-of-the-post/' ); ?></small></p>
            <?php
        }

        /**
         * Articles: Other render
         */
        function articles_other_render() {

            $breadcrumb = ( isset ( $this->settings['articles_breadcrumb'] ) && $this->settings['articles_breadcrumb'] == '1' ) ? 1 : 0;

            $sidebar_options = array(
                '' => __('None', 'supportifywp' ),
                'right' => __('Right', 'supportifywp'),
                'left' => __('Left', 'supportifywp')
            );

            $sidebar = ( isset ( $this->settings['articles_sidebar'] ) ) ? $this->settings['articles_sidebar'] : '';

            $icon_options = array(
                '' => __('None', 'supportifywp' ),
                'book' => __('Book', 'supportifywp'),
                'paper' => __('Paper with text', 'supportifywp'),
                'paper-light' => __('Paper with text (light)', 'supportifywp')
            );

            $icon = ( isset ( $this->settings['articles_icon'] ) ) ? $this->settings['articles_icon'] : 'paper-light';

            $excerpt_length = ( ! empty ( $this->settings['articles_excerpt_length'] ) ) ? $this->settings['articles_excerpt_length'] : sfywp_get_default_excerpt_length();

            $hide_title = ( isset ( $this->settings['articles_hide_title'] ) && $this->settings['articles_hide_title'] == '1' ) ? 1 : 0;

            ?>
            <h4><?php _e('Breadcrumb', 'supportifywp' ); ?></h4>
            <p>
                <input type="checkbox" id="sfywp_articles_breadcrumb" name="sfywp_settings[articles_breadcrumb]" value="1" <?php echo( $breadcrumb == 1 ? 'checked' : '' ); ?> />
                <label for="sfywp_articles_breadcrumb"><?php _e('Check in order to show a breadcrumb on archives and single articles', 'supportifywp'); ?></label>
            </p>

            <h4><?php _e('Sidebar', 'supportifywp' ); ?></h4>
            <p>
                <?php _e( 'The sidebar might be shown on archive and single article posts.', 'supportifywp' ); ?>
            </p>
            <p>
                <select id="sfywp_articles_sidebar" name="sfywp_settings[articles_sidebar]">
                    <?php foreach ( $sidebar_options as $key => $label ) { ?>
                        <option value="<?php echo $key; ?>" <?php selected( $sidebar, $key ); ?>><?php echo $label; ?></option>
                    <?php } ?>
                </select>
            </p>

            <h4><?php _e('Article Icon', 'supportifywp' ); ?></h4>
            <p>
                <select id="sfywp_articles_icon" name="sfywp_settings[articles_icon]">
                    <?php foreach ( $icon_options as $key => $label ) { ?>
                        <option value="<?php echo $key; ?>" <?php selected( $icon, $key ); ?>><?php echo $label; ?></option>
                    <?php } ?>
                </select>
            </p>

            <h4><?php _e('Excerpt Length', 'supportifywp' ); ?></h4>
            <p>
                <input id="sfywp_articles_excerpt_length" type="number" name="sfywp_settings[articles_excerpt_length]" value="<?php echo $excerpt_length; ?>">&nbsp;
                <small><?php _e( 'Maximum amount of words to be included.', 'supportifywp' ); ?></small>
            </p>

            <h4><?php _e('Hide titles', 'supportifywp' ); ?></h4>
            <p>
                <input type="checkbox" id="sfywp_articles_hide_title" name="sfywp_settings[articles_hide_title]" value="1" <?php echo( $hide_title == 1 ? 'checked' : '' ); ?> />
                <label for="sfywp_articles_hide_title"><?php _e('Check in order to hide page/post title on front page, archives and single articles', 'supportifywp'); ?></label>
                <br />
                <small><?php _e( 'This option only makes sense in case your theme is displaying the titles e.g. in the header.', 'supportifywp' ); ?></small>
            </p>

            <?php
        }

        function custom_css_render() {

            $custom_css_activated = ( isset ( $this->settings['custom_css_activated'] ) && $this->settings['custom_css_activated'] == '1' ) ? 1 : 0;
            $custom_css = ( !empty ( $this->settings['custom_css'] ) ) ? $this->settings['custom_css'] : '';
            ?>

            <p>
                <input type="checkbox" id="sfywp_custom_css_activated" name="sfywp_settings[custom_css_activated]" value="1" <?php echo( $custom_css_activated == 1 ? 'checked' : '' ); ?>>
                <label for="sfywp_custom_css_activated"><?php _e('Output custom CSS styles', 'supportifywp'); ?></label>
            </p>
            <br />
            <textarea id="sfywp_custom_css" name="sfywp_settings[custom_css]" rows="10" cols="80" style="width: 100%;"><?php echo stripslashes( $custom_css ); ?></textarea>
            <p>
                <small><?php _e("Please don't use the <code>style</code> tag. Simply paste you CSS declarations as follows: <code>.sfywp .sfywp-articles-category { background-color: #333; }</code>", 'supportifywp' ) ?></small>
            </p>

            <?php
        }

        function custom_content_wrapper_render() {

            $custom_content_wrapper_activated = ( isset ( $this->settings['custom_content_wrapper_activated'] ) && $this->settings['custom_content_wrapper_activated'] == '1' ) ? 1 : 0;
            $custom_content_wrapper = ( !empty ( $this->settings['custom_content_wrapper'] ) ) ? $this->settings['custom_content_wrapper'] : '';
            $custom_content_wrapper_end = ( !empty ( $this->settings['custom_content_wrapper_end'] ) ) ? $this->settings['custom_content_wrapper_end'] : '';
            ?>
            <p>
                <?php _e('Only in case your theme does not support Supportify by default, you can add custom HTML markup before and after the plugin outputs its content.', 'supportifywp' ); ?>
            </p>
            <br />
            <p>
                <input type="checkbox" id="sfywp_custom_content_wrapper_activated" name="sfywp_settings[custom_content_wrapper_activated]" value="1" <?php echo( $custom_content_wrapper_activated == 1 ? 'checked' : '' ); ?>>
                <label for="sfywp_custom_content_wrapper_activated"><?php _e('Output custom content wrapper HTML', 'supportifywp'); ?></label>
            </p>
            <br />
            <h4><?php _e('<u>Before</u> SupportifyWP content', 'supportifywp' ); ?></h4>
            <textarea id="sfywp_custom_css" name="sfywp_settings[custom_content_wrapper]" rows="3" cols="80" style="width: 100%;"><?php echo stripslashes( $custom_content_wrapper ); ?></textarea>
            <br />
            <h4><?php _e('<u>Before</u> SupportifyWP content', 'supportifywp' ); ?></h4>
            <textarea id="sfywp_custom_css" name="sfywp_settings[custom_content_wrapper_end]" rows="3" cols="80" style="width: 100%;"><?php echo stripslashes( $custom_content_wrapper_end ); ?></textarea>

            <?php
        }

        /**
         * Render Settings Page
         *
         * @access      public
         * @since       2.0.0
         * @return      void
         */
        public function render_settings_page() {

            if ( !current_user_can('edit_pages') ) {
                wp_die(__('You do not have sufficient permissions to access this page.', 'supportifywp'));
            }

            ?>
            <div class="sfywp sfywp-settings">
                <div class="wrap">
                    <?php screen_icon(); ?>
                    <h2><?php _e('SupportifyWP - Settings', 'supportifywp'); ?></h2>

                    <div id="poststuff">
                        <div id="post-body" class="metabox-holder columns-2">
                            <div id="post-body-content">
                                <div class="meta-box-sortables ui-sortable">
                                    <form action="options.php" method="post">
                                        <?php
                                        settings_fields('sfywp_settings');
                                        sfywp_admin_do_settings_sections('sfywp_settings');
                                        ?>

                                        <p><?php submit_button('Save Changes', 'button-primary', 'submit', false); ?></p>

                                        <input type="hidden" name="sfywp_settings[flush_rewrite_rules]" value="0" />
                                    </form>
                                </div>

                            </div>
                            <!-- /#post-body-content -->
                            <div id="postbox-container-1" class="postbox-container">
                                <div class="meta-box-sortables">
                                    <?php // TODO ?>
                                </div>
                                <!-- /.meta-box-sortables -->
                            </div>
                            <!-- /.postbox-container -->
                        </div>
                    </div>
                </div>
            </div>

            <?php
        }
    }

    new SFYWP_Settings();
}

/**
 * Custom settings section output
 *
 * Replacing: do_settings_sections('sfywp_settings');
 *
 * @param $page
 */
function sfywp_admin_do_settings_sections( $page ) {

    global $wp_settings_sections, $wp_settings_fields;

    if (!isset($wp_settings_sections[$page]))
        return;

    foreach ((array)$wp_settings_sections[$page] as $section) {

        $title = '';

        if ($section['title'])
            $title = "<h3 class='hndle'>{$section['title']}</h3>\n";

        if ($section['callback'])
            call_user_func($section['callback'], $section);

        if (!isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section['id']]))
            continue;

        echo '<div class="postbox">';
        echo $title;
        echo '<div class="inside">';
        echo '<table class="form-table">';
        do_settings_fields($page, $section['id']);
        echo '</table>';
        echo '</div>';
        echo '</div>';
    }
}