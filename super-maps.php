<?php
/**
 * Plugin Name: SuperMaps
 * Plugin URI: http://www.headmade.rs
 * Description: Plugin for google maps
 * Author: headmade.rs
 * Version: 1.6
 * Author URI: http://www.headmade.rs
 * Text Domain: super-maps
 * Domain Path: /languages/
 */


/**
 *
 * Plugin Settings
 *
 */

global $wpdb;

define('SUPERMAPS_VERSION', '1.6');
define('SUPERMAPS_DB_POLYGON', $wpdb->prefix . 'super_maps_polygon');
define('SUPERMAPS_DB_LINE', $wpdb->prefix . 'super_maps_line');
define('SUPERMAPS_DB_MAP', $wpdb->prefix . 'super_maps_map');
define('SUPERMAPS_DB_MARKER', $wpdb->prefix . 'super_maps_marker');
define('SUPERMAPS_DB_STYLE_POLYGON', $wpdb->prefix . 'super_maps_style_polygon');
define('SUPERMAPS_DB_STYLE_LINE', $wpdb->prefix . 'super_maps_style_line');
define('SUPERMAPS_DB_STYLE_MARKER', $wpdb->prefix . 'super_maps_style_marker');
define('SUPERMAPS_DB_STYLE_MAP', $wpdb->prefix . 'super_maps_style_map');



// Install table

register_activation_hook(__FILE__, 'super_maps_db_install');

register_deactivation_hook(__FILE__, 'super_maps_db_uninstall');

/**
 *
 * Include files for project
 *
 */

// include database create
include(plugin_dir_path(__FILE__) . 'includes/super-maps-db.php');

// include public shortcode
include(plugin_dir_path(__FILE__) . 'includes/super-maps-shortcode.php');

// include map html
include(plugin_dir_path(__FILE__) . 'includes/super-maps-google-map.php');

// include maps
include(plugin_dir_path(__FILE__) . 'includes/super-maps-map.php');
include(plugin_dir_path(__FILE__) . 'includes/super-maps-map-style.php');

// inclide marker
include(plugin_dir_path(__FILE__) . 'includes/super-maps-marker.php');
include(plugin_dir_path(__FILE__) . 'includes/super-maps-marker-style.php');

// include polygon
include(plugin_dir_path(__FILE__) . 'includes/super-maps-polygon.php');
include(plugin_dir_path(__FILE__) . 'includes/super-maps-polygon-style.php');

// include line
include(plugin_dir_path(__FILE__) . 'includes/super-maps-line.php');
include(plugin_dir_path(__FILE__) . 'includes/super-maps-line-style.php');

// inclide settings
include(plugin_dir_path(__FILE__) . 'includes/super-maps-settings.php');


/**
 *
 * Admin script for plugin
 *
 */

// fix for redirect
add_action('init', 'super_maps_do_output_buffer');
function super_maps_do_output_buffer()
{
    ob_start();
}

// media upload for edit page
function super_maps_manager_admin_scripts()
{
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_script('jquery');
}

// thickbox for edit page
function super_maps_manager_admin_styles()
{
    wp_enqueue_style('thickbox');
}

// hook media upload
add_action('admin_print_scripts', 'super_maps_manager_admin_scripts');
add_action('admin_print_styles', 'super_maps_manager_admin_styles');


// hook page for admin menu
add_action('admin_menu', 'super_maps_page');

function super_maps_page()
{
    add_menu_page('SuperMaps', 'SuperMaps', 'manage_options', 'super-maps', 'super_maps_route_page', plugins_url('/super-maps/images/super-maps.png'));

}

// polygon main page

function super_maps_route_page()
{

    super_maps_header_menu();

    if(!empty($_GET['p'])){
        $p = $_GET['p'];
    }else{
        $p = '';
    }

    switch ($p) {

        case 'polygon':

            super_maps_polygon_list();
            super_maps_polygon_style();

            break;

        case 'newPolygon':

            super_maps_polygon_add();

            break;

        case 'editPolygon';

            super_maps_polygon_edit();

            break;

        case 'delPolygon':

            super_maps_polygon_del();

            break;

        case 'newPolygonStyle':

            super_maps_polygon_style_add();

            break;

        case 'editPolygonStyle':

            super_maps_polygon_style_edit();

            break;

        case 'delPolygonStyle':

            super_maps_polygon_style_del();

            break;

        case 'line':

            super_maps_line_list();
            super_maps_line_style();

            break;

        case 'newLine':

            super_maps_line_add();

            break;

        case 'editLine';

            super_maps_line_edit();

            break;

        case 'delLine':

            super_maps_line_del();

            break;

        case 'newLineStyle':

            super_maps_line_style_add();

            break;

        case 'editLineStyle':

            super_maps_line_style_edit();

            break;

        case 'delLineStyle':

            super_maps_line_style_del();

            break;

        case 'map':

            super_maps_map();
            super_maps_map_style();

            break;

        case 'newMap':

            super_maps_map_add();

            break;

        case 'editMap':

            super_maps_map_edit();

            break;

        case 'delMap':

            super_maps_map_del();

            break;

        case 'newMapStyle':

            super_maps_map_style_add();

            break;

        case 'editMapStyle':

            super_maps_map_style_edit();

            break;

        case 'delMapStyle':

            super_maps_map_style_del();

            break;

        case 'marker':

            super_maps_marker();
            super_maps_marker_style();

            break;

        case 'newMarker':

            super_maps_marker_add();

            break;

        case 'editMarker':

            super_maps_marker_edit();

            break;

        case 'delMarker':

            super_maps_marker_del();
            break;

        case 'newMarkerStyle':

            super_maps_marker_style_add();

            break;

        case 'editMarkerStyle':

            super_maps_marker_style_edit();

            break;

        case 'delMarkerStyle':

            super_maps_marker_style_del();
            break;

        case 'settings':

            super_maps_settings();
            break;

        default:

            super_maps();
            break;

    }


}

// css hook

add_action('admin_head', 'super_maps_css');

function super_maps_css()
{

   echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('super-maps/css/style.css') . '">';


}

function super_maps_custom_js() {


    echo "<script>
        if (typeof google != 'object' && typeof google.maps != 'object') {
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'http://maps.google.com/maps/api/js?sensor=false';
        document.body.appendChild(script);
    }</script>";


}
// Add hook for admin <head></head>
add_action('admin_head', 'super_maps_custom_js');
// Add hook for front-end <head></head>
add_action('wp_head', 'super_maps_custom_js');


// Polygon header

function super_maps_header_menu()
{
    if(!empty($_GET['p'])) {
        $p = $_GET['p'];
    }else{
        $p ='';
    }

    $print = "<div class='header-menu'>";
    $print .= "<ul>";
    $print .= "<li";
    if (empty($p)) {
        $print .= " class='active' ";
    }
    $print .= "><a href='admin.php?page=super-maps'><span class='dashicons dashicons-admin-home'></span></a></li>";
    $print .= "<li";
    if ($p == 'marker') {
        $print .= " class='active' ";
    }
    $print .= "><a href='admin.php?page=super-maps&p=marker'><span class='dashicons dashicons-location'></span> " . __('Lista markera', 'super-maps') . "</a></li>";
    $print .= "<li";
    if ($p == 'line') {
        $print .= " class='active' ";
    }
    $print .= "><a href='admin.php?page=super-maps&p=line'><span class='dashicons dashicons-chart-area'></span> " . __('Lista linija', 'super-maps') . "</a></li>";

    $print .= "<li";
    if ($p == 'polygon') {
        $print .= " class='active' ";
    }
    $print .= "><a href='admin.php?page=super-maps&p=polygon'><span class='dashicons dashicons-chart-area'></span> " . __('Lista poligona', 'super-maps') . "</a></li>"
    ;
    $print .= "<li";
    if ($p == 'map') {
        $print .= " class='active' ";
    }

    $print .= "><a href='admin.php?page=super-maps&p=map'><span class='dashicons dashicons-location-alt'></span> " . __('Mape', 'super-maps') . "</a></li>";
    $print .= "<li";
    if ($p == 'settings') {
        $print .= " class='active' ";
    }
    $print .= "><a href='admin.php?page=super-maps&p=settings'><span class='dashicons dashicons-admin-generic'></span> " . __('Podešavanja', 'super-maps') . "</a></li>";
    $print .= "</ul>";
    $print .= "</div>";

    echo $print;
}

function super_maps()
{

    $short_code = "[super_maps map='1']";

    $print = '<div class="super-maps">';

    $print .= '<p>'.__('Shortcode za prikazivanje mape:', 'super-maps').'</p>';

    $print .= '<p>'.__('1 označava ID mape', 'super-maps').'</p>';

    $print .= '<input type="text" value="'.$short_code.'" disabled><br><br>';



    $print .= '</div>';

    echo $print;





}

function redirect_js($url){

    echo '<script type="text/javascript">';
    echo 'window.location.href="'.$url.'";';
    echo '</script>';
    echo '<noscript>';
    echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
    echo '</noscript>'; exit;


}