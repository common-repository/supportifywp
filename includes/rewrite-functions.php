<?php
/*
 * Add post id to article
 * Source: https://duogeek.com/blog/how-to-append-post-id-at-the-end-of-the-url-for-a-custom-post-type/
 */

function sfywp_post_type_link( $link, $post = 0, $leavename ){
    if ( $post->post_type == 'sfywp_article' ) {
        //return str_replace('%post_id%', $post->ID, $link);
        return home_url( SFYWP_ARTICLES_SLUG . '/'. $post->post_name .'-'. $post->ID . '/' );
    } else {
        return $link;
    }
}

//add_filter('post_type_link', 'sfywp_post_type_link', 1, 3);

/*
 * Add rewrite rules
 */
function sfywp_rewrite_rules() {

    /*
    add_rewrite_rule(
        'blub/category/([^/]+)/?',
        'index.php?page_id=' . $category_archive_page_id . '&sfywp_category=$matches[1]',
        'top'
    );
    */

    // Single article
    add_rewrite_rule(
        SFYWP_ARTICLES_SLUG . '/([^/]+)-([0-9]+)?/$',
        'index.php?post_type=sfywp_article&p=$matches[1]&postid=$matches[2]',
        'top' );

    // Categories
    add_rewrite_rule(
        SFYWP_ARTICLES_CATEGORY_SLUG . '/([^/]+)/?',
        'index.php?page_id=' . SFYWP_ARTICLES_FRONT_PAGE . '&sfywp_category=$matches[1]',
        'top'
    );

    //flush_rewrite_rules(); // TODO works!

}
add_action('init', 'sfywp_rewrite_rules');

/*
 * Add query strings
 */
function sfywp_rewrite_tags()
{
    add_rewrite_tag(
        '%sfywp_category%',
        '([^/]+)'
    );

}
add_action('init', 'sfywp_rewrite_tags');