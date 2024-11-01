<?php
/**
 * Template functions
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Add body classes for our pages pages.
 *
 * @param  array $classes
 * @return array
 */
function sfywp_body_class( $classes ) {

    $classes = (array) $classes;

    if ( sfywp_is_articles() ) {
        $classes[] = 'sfywp-page';
    }

    return array_unique( $classes );
}
add_filter( 'body_class', 'sfywp_body_class' );

/**
 * Load our template instead of the theme templates.
 *
 * @param  mixed  $template
 * @return string
 */
function sfywp_template_include( $template ) {

    $template_path = apply_filters( 'sfywp_template_path', 'supportifywp/' );

    $find = array();
    $file = '';

    if ( sfywp_is_articles_front_page() ) {
        $file   = 'articles-front-page.php';
        $find[] = $file;
        $find[] = $template_path . $file;
    } elseif ( is_single() && get_post_type() == SFYWP_ARTICLES_CPT ) {
        $file   = 'articles-single.php';
        $find[] = $file;
        $find[] = $template_path . $file;
    } elseif ( sfywp_is_articles_archive() ) {
        $file   = 'articles-archive.php';
        $find[] = $file;
        $find[] = $template_path . $file;
    }

    if ( $file ) {
        $template = locate_template( array_unique( $find ) ) ;
        if ( ! $template ) {
            $template = SFYWP_TEMPLATES_DIR . $file;
        }
    }

    return $template;
}
add_filter( 'template_include', 'sfywp_template_include' );

/**
 * Content wrapper (start) in order to support common theme layouts
 */
function sfywp_output_main_content_wrapper() {

    global $sfywp_settings;

    // Custom Wrapper
    if ( isset ( $sfywp_settings['custom_content_wrapper_activated'] ) && $sfywp_settings['custom_content_wrapper_activated'] == '1' && ! empty( $sfywp_settings['custom_content_wrapper'] ) ) {

        echo $sfywp_settings['custom_content_wrapper'];

    // Theme compatibility
    } else {

        $template = get_option( 'template' );

        switch ( $template ) {
            case 'twentyfourteen' :
                echo '<div id="primary" class="content-area"><div id="content" role="main" class="site-content twentyfourteen"><div class="tfwc">';
                break;
            case 'twentyfifteen' :
                echo '<div id="primary" role="main" class="content-area twentyfifteen"><div id="main" class="site-main t15wc">';
                break;
            case 'twentysixteen' :
                echo '<div id="primary" class="content-area twentysixteen"><main id="main" class="site-main" role="main">';
                break;
            case 'twentyseventeen' :
                echo '<div class="wrap"><div id="primary" class="content-area twentyseventeen"><main id="main" class="site-main" role="main">';
                break;
            case 'checkout' :
                echo '<div id="container" class="container"><main id="main" class="site-main" role="main"><div id="primary" class="content-area checkout-theme">';
                break;
            default :
                echo '<div id="container" class="container"><main id="main" class="site-main" role="main"><div id="primary" class="content-area">';
                break;
        }
    }

    // Opening our content container
    echo '<div id="sfywp-content" class="sfywp-content">';
}
add_action( 'sfywp_before_main_content', 'sfywp_output_main_content_wrapper', 10 );

/**
 * Content wrapper (end) in order to support common theme layouts
 */
function sfywp_output_main_content_wrapper_end() {

    // Closing our content container
    echo '</div>';

    // Custom Wrapper
    if ( isset ( $sfywp_settings['custom_content_wrapper_activated'] ) && $sfywp_settings['custom_content_wrapper_activated'] == '1' && ! empty( $sfywp_settings['custom_content_wrapper_end'] ) ) {

        echo $sfywp_settings['custom_content_wrapper_end'];

    // Theme compatibility
    } else {

        $template = get_option( 'template' );

        switch ( $template ) {
            case 'twentyfourteen' :
                echo '</div></div></div>';
                break;
            case 'twentyfifteen' :
                echo '</div></div>';
                break;
            case 'twentysixteen' :
                echo '</main></div>';
                break;
            case 'twentyseventeen' :
                echo '</main></div></div>';
                break;
            default :
                echo '</div></div></div>';
                break;
        }
    }
}
add_action( 'sfywp_after_main_content', 'sfywp_output_main_content_wrapper_end', 10 );

/**
 * Display the title.
 *
 * @param string $before
 * @param string $after
 */
function sfywp_the_title( $before = '', $after = '' ) {

    global $sfywp_settings;

    if ( ( sfywp_is_articles_front_page() || sfywp_is_articles_singular() ) && isset( $sfywp_settings['articles_hide_title'] ) && $sfywp_settings['articles_hide_title'] == '1' )
        return;

    $title = get_the_title();

    if ( ! empty( $title ) ) {
        echo $before . $title . $after;
    }
}

/**
 * Display the archive title based on the queried object.
 *
 * @param string $before
 * @param string $after
 */
function sfywp_the_archive_title( $before = '', $after = '' ) {

    global $sfywp_settings;

    if ( sfywp_is_articles_archive() && isset( $sfywp_settings['articles_hide_title'] ) && $sfywp_settings['articles_hide_title'] == '1' )
        return;

    $title = sfywp_get_archive_title();

    if ( ! empty( $title ) ) {
        echo $before . $title . $after;
    }
}

/**
 * Display the archive description based on the queried object.
 *
 * @param string $before
 * @param string $after
 */
function sfywp_the_archive_description( $before = '', $after = '' ) {

    global $sfywp_settings;

    if ( sfywp_is_articles_archive() && isset( $sfywp_settings['articles_hide_archive_description'] ) && $sfywp_settings['articles_hide_archive_description'] == '1' )
        return;

    $description = sfywp_get_archive_description();

    if ( ! empty( $description ) ) {
        echo $before . $description . $after;
    }
}

/**
 * Display the post excerpt
 *
 * @param string $before
 * @param string $after
 */
function sfywp_the_excerpt( $before = '', $after = '' ) {

    $excerpt = sfywp_get_excerpt();

    if ( ! empty( $excerpt ) ) {
        echo $before . $excerpt . $after;
    }
}

/**
 * Display the post meta
 */
function sfywp_the_meta() {

    // Post views
    sfywp_the_post_views();
}

/**
 * Check whether to show excerpts or not
 *
 * @return bool
 */
function sfywp_show_articles_excerpt() {

    global $sfywp_settings;

    if ( sfywp_is_articles_front_page() && empty( $sfywp_settings['articles_front_page_excerpt'] ) )
        return false;

    if ( sfywp_is_articles_archive() && empty( $sfywp_settings['articles_archives_excerpt'] ) )
        return false;

    return true;
}

/**
 * Check whether to show sidebar or not
 *
 * @return bool
 */
function sfywp_get_sidebar() {

    global $sfywp_settings;

    $sidebar = '';

    if ( ( sfywp_is_articles_singular() || sfywp_is_articles_archive() ) && ! empty( $sfywp_settings['articles_sidebar'] ) )
        $sidebar = 'sfywp-articles';

    // Hook
    $sidebar = apply_filters( 'sfywp_sidebar', $sidebar );

    // Finally return
    return $sidebar;
}

/**
 * Get articles categories to be displayed on front page
 *
 * @return array|int|WP_Error
 */
function sfywp_get_articles_front_page_categories() {
    return sfywp_get_article_categories();
}

/**
 * Get articles category posts to be displayed on front page
 *
 * @param $category_name
 * @return WP_Query
 */
function sfywp_get_articles_front_page_category_posts( $category ) {

    global $sfywp_settings;

    $args = array(
        'posts_per_page' => ( ! empty ( $sfywp_settings['articles_front_page_items'] ) ) ? intval( $sfywp_settings['articles_front_page_items'] ) : -1,
        'sfywp_article_category' => $category
    );

    $args['sfywp_orderby'] = ( ! empty( $sfywp_settings['articles_front_page_orderby'] ) ) ? $sfywp_settings['articles_front_page_orderby'] : sfywp_get_articles_orderby_default();
    $args['sfywp_order'] = ( ! empty( $sfywp_settings['articles_front_page_order'] ) ) ? $sfywp_settings['articles_front_page_order'] : sfywp_get_order_default();

    $posts = sfywp_get_articles( $args );

    return $posts;
}

/**
 * Collecting template article classes
 *
 * @param string $classes
 * @return string
 */
function sfywp_the_article_classes( $classes = '' ) {

    $icon = sfywp_get_article_icon();

    // Maybe add icon
    if ( ! empty( $icon ) ) {
        $classes .= ' sfywp-article--icon sfywp-article--icon-' . esc_html( $icon );
    }

    $classes = apply_filters( 'sfywp_the_article_classes', $classes );

    // Output
    if ( ! empty( $classes ) )
        echo 'class="' . $classes . '"';
}

/**
 * Collecting template container classes
 *
 * @param string $classes
 * @return string
 */
function sfywp_the_container_classes( $classes = '' ) {

    global $sfywp_settings;

    // Sidebar
    if ( sfywp_get_sidebar() && ! empty( $sfywp_settings['articles_sidebar'] ) )
        $classes .= ( 'left' === $sfywp_settings['articles_sidebar'] ) ? ' sfywp-container--sidebar-inverted' : ' sfywp-container--sidebar';

    // Filter
    $classes = apply_filters( 'sfywp_the_classes', $classes );

    // Output
    if ( ! empty( $classes ) )
        echo 'class="' . $classes . '"';
}

/**
 * Display dynamic sidebar inside templates
 *
 * @param string $sidebar
 * @param string $before
 * @param string $after
 */
function sfywp_the_sidebar( $sidebar = '', $before = '<div class="sfywp-sidebar">', $after = '</div>' ) {

    if ( empty( $sidebar ) ) {
        $sidebar = sfywp_get_sidebar();
    }

    // Maybe display sidebar
    if ( ! empty( $sidebar ) ) {
        echo $before;
        dynamic_sidebar( $sidebar );
        echo $after;
    }
}

/**
 * Collecting template article widget list classes
 *
 * @param string $classes
 */
function sfywp_the_article_widget_list_classes( $classes = '' ) {

    // Maybe add icon
    $icon = sfywp_get_article_icon();

    if ( ! empty( $icon ) ) {
        $classes .= ' sfywp-widget-list--icon sfywp-widget-list--icon-' . esc_html( $icon );
    }

    // Output
    if ( ! empty( $classes ) )
        echo 'class="' . $classes . '"';
}

/**
 * Collecting template article widget list item classes
 *
 * @param string $classes
 * @param int $term_id
 */
function sfywp_the_article_widget_list_item_classes( $classes = '', $term_id = 0 ) {

    // Active
    if ( ! empty( $term_id ) && has_term( $term_id, SFYWP_ARTICLES_CATEGORY_TAX, get_the_ID() ) ) {
        $classes .= ' sfywp-widget-list__item--active';

    } elseif ( empty( $term_id ) && sfywp_is_current_article( get_the_ID() ) ) {
        $classes .= ' sfywp-widget-list__item--active';
    }

    // Debug
    //$classes .= ' termid-'. $term_id;
    //$classes .= ' postid-'. get_the_ID();

    // Output
    if ( ! empty( $classes ) )
        echo 'class="' . $classes . '"';
}

/**
 * Display the articles category more link
 *
 * @param $category
 */
function sfywp_the_articles_category_more( $category ) {

    //global $sfywp_settings;

    //$items_shown = ( ! empty ( $sfywp_settings['articles_front_page_items'] ) && '-1' != $sfywp_settings['articles_front_page_items'] ) ? intval( $sfywp_settings['articles_front_page_items'] ) : 99999;
    //$items_hidden = ( isset( $category->count ) ) ? $category->count - $items_shown : 0;

    //if ( $items_hidden > 0 ) {

        $text = __( 'View all', 'supportifywp' );

        ?>
        <a class="sfywp-more-link" href="<?php echo get_term_link( $category->name, SFYWP_ARTICLES_CATEGORY_TAX ); ?>" title="<?php echo $text; ?>">
            &rarr; <?php echo $text; ?>
        </a>
        <?php
    //}
}

/**
 * Load breadcrumb based on several conditions
 */
function sfywp_maybe_include_breadcrumb() {

    global $sfywp_settings;

    if ( ! isset( $sfywp_settings['articles_breadcrumb'] ) || '1' != $sfywp_settings['articles_breadcrumb'] )
        return;

    if ( empty( $sfywp_settings['articles_front_page'] ) || get_the_ID() == $sfywp_settings['articles_front_page'] )
        return;

    sfywp_the_breadcrumb();
}
add_action( 'sfywp_before_main_content', 'sfywp_maybe_include_breadcrumb', 50 );