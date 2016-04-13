<?php
/*
Plugin Name: SuperMaps
Plugin URI: http://webinvade.rs
Description: SuperMaps plugin
Author: WebInvade.rs
Version: 1.0
Author URI: http://webinvade.rs
Text Domain: super-maps
*/



add_action('after_setup_theme', 'check_framework');

function check_framework()
{

    if (!function_exists('cs_framework_init') && !class_exists('CSFramework')) {

        require_once plugin_dir_path(__FILE__) . 'codestar-framework/cs-framework.php';

    } else {

        require_once plugin_dir_path(__FILE__) . 'include/exist-framework.php';
    }

}

require_once plugin_dir_path(__FILE__) . 'include/exist-framework.php';


// Shortcode
include(plugin_dir_path(__FILE__) . 'shortcode/map.php');

function super_maps_admin_enqueue()
{

    wp_enqueue_style('supermaps-css', plugins_url('/css/css.css'));

    if(function_exists('cs_get_option')) {
        $google_api_key = cs_get_option('google_maps_api_key');
    }


    if (!empty($google_api_key)) {
        wp_enqueue_script('google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $google_api_key . '&libraries=drawing');
    } else {
        wp_enqueue_script('google-maps', 'http://maps.google.com/maps/api/js?sensor=false&libraries=drawing', array(), 1.0);
    }

    switch (get_current_screen()->post_type) {
        case 'supermaps':
            wp_enqueue_script('supermaps-admin-js', plugins_url('/js/supermaps-admin-map.js', __FILE__));
            break;
        case 'supermaps_marker':
            wp_enqueue_script('supermaps-admin-js', plugins_url('/js/supermaps-admin-map.js', __FILE__));
            break;
        case 'supermaps_line':
            wp_enqueue_script('supermaps-admin-js', plugins_url('/js/supermaps-admin-lines.js', __FILE__));
            break;
        case 'supermaps_polygon':
            wp_enqueue_script('supermaps-admin-js', plugins_url('/js/supermaps-admin-polygon.js', __FILE__));
            break;
        case 'supermaps_overlay':
            wp_enqueue_script('supermaps-admin-js', plugins_url('/js/supermaps-admin-map.js', __FILE__));
            break;
    }


}

add_action('admin_enqueue_scripts', 'super_maps_admin_enqueue');

function super_maps_enqueue()
{
    wp_enqueue_script('google-maps', 'http://maps.google.com/maps/api/js?sensor=false', array(), 1.0);
}

add_action('wp_enqueue_scripts', 'super_maps_enqueue');

/**
 *  Create Maps post type
 */

add_action('init', 'super_maps_create_maps');

function super_maps_create_maps()
{

    load_plugin_textdomain('super-maps', false, basename(dirname(__FILE__)) . '/languages');

    register_post_type('supermaps',
        array(
            'labels' => array(
                'name' => __('Maps', 'super-maps'),
                'singular_name' => __('Map', 'super-maps'),
                'add_new' => __('Add Map', 'super-maps'),
                'add_new_item' => __('Add Map', 'super-maps'),
                'edit' => __('Edit', 'super-maps'),
                'edit_item' => __('Edit Map', 'super-maps'),
                'new_item' => __('New Map', 'super-maps'),
                'view' => __('View', 'super-maps'),
                'view_item' => __('View Map', 'super-maps'),
                'search_items' => __('Search Map', 'super-maps'),
                'not_found' => __('No Maps Found', 'super-maps'),
                'not_found_in_trash' => __('There is no Maps in trash', 'super-maps'),
                'parent' => __('Parent Map', 'super-maps')
            ),
            'description' => __('Maps', 'super-maps'),
            'public' => true,
            'menu_position' => 29,
            'supports' => array('title'),
            'menu_icon' => 'dashicons-location-alt',
            'has_archive' => true
        )
    );

    register_post_type('supermaps_marker',
        array(
            'labels' => array(
                'name' => __('Markers', 'super-maps'),
                'singular_name' => __('Marker', 'super-maps'),
                'add_new' => __('Add Marker', 'super-maps'),
                'add_new_item' => __('Add Marker', 'super-maps'),
                'edit' => __('Edit', 'super-maps'),
                'edit_item' => __('Edit Marker', 'super-maps'),
                'new_item' => __('New Marker', 'super-maps'),
                'view' => __('View', 'super-maps'),
                'view_item' => __('View Marker', 'super-maps'),
                'search_items' => __('Search Marker', 'super-maps'),
                'not_found' => __('No Markers Found', 'super-maps'),
                'not_found_in_trash' => __('There is no Markers in trash', 'super-maps'),
                'parent' => __('Parent Marker', 'super-maps')
            ),
            'description' => __('Markers', 'super-maps'),
            'public' => true,
            'show_in_menu' => 'edit.php?post_type=supermaps',
            'supports' => array('title'),
            'has_archive' => true
        )
    );

    register_post_type('supermaps_line',
        array(
            'labels' => array(
                'name' => __('Lines', 'super-maps'),
                'singular_name' => __('Lines', 'super-maps'),
                'add_new' => __('Add Lines', 'super-maps'),
                'add_new_item' => __('Add Lines', 'super-maps'),
                'edit' => __('Edit', 'super-maps'),
                'edit_item' => __('Edit Lines', 'super-maps'),
                'new_item' => __('New Lines', 'super-maps'),
                'view' => __('View', 'super-maps'),
                'view_item' => __('View Lines', 'super-maps'),
                'search_items' => __('Search Lines', 'super-maps'),
                'not_found' => __('No Lines Found', 'super-maps'),
                'not_found_in_trash' => __('There is no Lines in trash', 'super-maps'),
                'parent' => __('Parent Lines', 'super-maps')
            ),
            'description' => __('Lines', 'super-maps'),
            'public' => true,
            'show_in_menu' => 'edit.php?post_type=supermaps',
            'supports' => array('title'),
            'has_archive' => true
        )
    );

    register_post_type('supermaps_polygon',
        array(
            'labels' => array(
                'name' => __('Polygon', 'super-maps'),
                'singular_name' => __('Polygon', 'super-maps'),
                'add_new' => __('Add Polygon', 'super-maps'),
                'add_new_item' => __('Add Polygon', 'super-maps'),
                'edit' => __('Edit', 'super-maps'),
                'edit_item' => __('Edit Polygon', 'super-maps'),
                'new_item' => __('New Polygon', 'super-maps'),
                'view' => __('View', 'super-maps'),
                'view_item' => __('View Polygon', 'super-maps'),
                'search_items' => __('Search Polygon', 'super-maps'),
                'not_found' => __('No Polygon Found', 'super-maps'),
                'not_found_in_trash' => __('There is no Polygon in trash', 'super-maps'),
                'parent' => __('Parent Polygon', 'super-maps')
            ),
            'description' => __('Polygon', 'super-maps'),
            'public' => true,
            'show_in_menu' => 'edit.php?post_type=supermaps',
            'supports' => array('title'),
            'has_archive' => true
        )
    );


    register_post_type('supermaps_overlay',
        array(
            'labels' => array(
                'name' => __('Overlays', 'super-maps'),
                'singular_name' => __('Overlay', 'super-maps'),
                'add_new' => __('Add Overlay', 'super-maps'),
                'add_new_item' => __('Add Overlay', 'super-maps'),
                'edit' => __('Edit', 'super-maps'),
                'edit_item' => __('Edit Overlay', 'super-maps'),
                'new_item' => __('New Overlay', 'super-maps'),
                'view' => __('View', 'super-maps'),
                'view_item' => __('View Overlay', 'super-maps'),
                'search_items' => __('Search Overlay', 'super-maps'),
                'not_found' => __('No Overlays Found', 'super-maps'),
                'not_found_in_trash' => __('There is no Overlays in trash', 'super-maps'),
                'parent' => __('Parent Overlay', 'super-maps')
            ),
            'description' => __('Overlays', 'super-maps'),
            'public' => true,
            'show_in_menu' => 'edit.php?post_type=supermaps',
            'supports' => array('title'),
            'has_archive' => true
        )
    );


}

function supermaps_add_shortcode_columns($columns)
{

    return array_merge($columns,
        array('shortcode' => __('Shortcode'),
        ));
}

add_filter('manage_supermaps_posts_columns', 'supermaps_add_shortcode_columns');


add_action('manage_posts_custom_column', 'supermaps_menage_shortcode_columns', 10, 2);

function supermaps_menage_shortcode_columns($column, $post_id)
{
    switch ($column) {
        case 'shortcode':
            echo "[supermaps map='" . $post_id . "'] ";
            break;
    }
}
