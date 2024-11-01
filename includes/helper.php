<?php
/**
 * Helper functions
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Convert objects to array
 *
 * @param $objects
 * @return array|null
 */
function sfywp_objects_to_array($objects) {

    if ( is_array($objects) ) {
        $array = array();

        foreach ($objects as $object) {
            $array[] = get_object_vars($object);
        }

        return $array;

    } elseif ( is_object( $objects ) ) {
        get_object_vars($objects);

    } else {
        return null;
    }
}

/**
 * Sort associative array by value
 *
 * @param $array2sort
 * @param $sortby
 * @param int $sort
 * @return null
 */
function sfywp_sort_associative_array_by_value($array2sort, $sortby, $sort = SORT_DESC) {

    if ( !$array2sort || !is_array( $array2sort ) )
        return null;

    $key_values = array();

    foreach ($array2sort as $key => $row) {
        $key_values[$key] = $row[$sortby];
    }

    array_multisort($key_values, $sort, $array2sort);

    return $array2sort;
}

/**
 * Check if user is admin
 *
 * @return bool
 */
function sfywp_user_is_admin() {
    return ( current_user_can('editor') || current_user_can('administrator') ) ? true : false;
}

/**
 * Debug
 *
 * @param $args
 * @param bool $title
 */
function sfywp_debug( $args, $title = false ) {

    if ( $title )
        echo '<h3>' . $title . '</h3>';

    echo '<pre>';
    print_r( $args );
    echo '</pre>';
}