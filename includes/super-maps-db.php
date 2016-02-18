<?php


global $db_version;
$db_version = '1.0';


function super_maps_db_uninstall()
{
    global $wpdb;

    $wpdb->query("DROP TABLE IF EXISTS " . SUPERMAPS_DB_POLYGON);

    $wpdb->query("DROP TABLE IF EXISTS " . SUPERMAPS_DB_LINE);

    $wpdb->query("DROP TABLE IF EXISTS " . SUPERMAPS_DB_MAP);

    $wpdb->query("DROP TABLE IF EXISTS " . SUPERMAPS_DB_MARKER);

    $wpdb->query("DROP TABLE IF EXISTS " . SUPERMAPS_DB_STYLE_POLYGON);

    $wpdb->query("DROP TABLE IF EXISTS " . SUPERMAPS_DB_STYLE_MARKER);

    $wpdb->query("DROP TABLE IF EXISTS " . SUPERMAPS_DB_STYLE_MAP);

    $wpdb->query("DROP TABLE IF EXISTS " . SUPERMAPS_DB_STYLE_LINE);

}


function super_maps_db_install()
{
    global $wpdb;
    global $db_version;


    $charset_collate = '';

    if (!empty($wpdb->charset)) {
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
    }

    if (!empty($wpdb->collate)) {
        $charset_collate .= " COLLATE {$wpdb->collate}";
    }

    $sql_polygon = "CREATE TABLE IF NOT EXISTS " . SUPERMAPS_DB_POLYGON . " (
id int(11) NOT NULL AUTO_INCREMENT,
name varchar(255) NOT NULL,
map int(11) NOT NULL,
style int(11) NOT NULL,
poligon_cord text NOT NULL,
html text NOT NULL,
date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
active int(11) NOT NULL,
PRIMARY KEY (id)
) " . $charset_collate;

    $sql_polygon_style = "CREATE TABLE IF NOT EXISTS " . SUPERMAPS_DB_STYLE_POLYGON . " (
id int(11) NOT NULL AUTO_INCREMENT,
name varchar(255) NOT NULL,
stroke_color varchar(255) NOT NULL,
stroke_opacity varchar(255) NOT NULL,
stroke_weight varchar(255) NOT NULL,
fill_color varchar(255) NOT NULL,
fill_opacity varchar(255) NOT NULL,
PRIMARY KEY (id)
) " . $charset_collate;

    $sql_line = "CREATE TABLE IF NOT EXISTS " . SUPERMAPS_DB_LINE . " (
id int(11) NOT NULL AUTO_INCREMENT,
name varchar(255) NOT NULL,
map int(11) NOT NULL,
style int(11) NOT NULL,
poligon_cord text NOT NULL,
html text NOT NULL,
date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
active int(11) NOT NULL,
PRIMARY KEY (id)
) " . $charset_collate;

    $sql_line_style = "CREATE TABLE IF NOT EXISTS " . SUPERMAPS_DB_STYLE_LINE . " (
id int(11) NOT NULL AUTO_INCREMENT,
name varchar(255) NOT NULL,
stroke_color varchar(255) NOT NULL,
stroke_opacity varchar(255) NOT NULL,
stroke_weight varchar(255) NOT NULL,
fill_color varchar(255) NOT NULL,
fill_opacity varchar(255) NOT NULL,
PRIMARY KEY (id)
) " . $charset_collate;


    $sql_map = "CREATE TABLE IF NOT EXISTS " . SUPERMAPS_DB_MAP . " (
id int(11) NOT NULL AUTO_INCREMENT,
name varchar(255) NOT NULL,
style int(11) NOT NULL,
load_variable varchar(255) NOT NULL,
longitude varchar(255) NOT NULL,
latitude varchar(255) NOT NULL,
scroll int(11) NOT NULL,
zoom int(11) NOT NULL,
layerLine int(11) NOT NULL,
layerPolygon int(11) NOT NULL,
layerMarker int(11) NOT NULL,
active int(11) NOT NULL,
date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (id)
) " . $charset_collate;


    $sql_map_style = "CREATE TABLE " . SUPERMAPS_DB_STYLE_MAP . " (
id int(11) NOT NULL AUTO_INCREMENT,
name varchar(255) NOT NULL,
file varchar(255) NOT NULL,
width varchar(255) NOT NULL,
height varchar(255) NOT NULL,
theme_array text NOT NULL,
PRIMARY KEY (id)
) " . $charset_collate;

    $sql_marker = "CREATE TABLE IF NOT EXISTS " . SUPERMAPS_DB_MARKER . " (
id int(11) NOT NULL AUTO_INCREMENT,
name varchar(255) NOT NULL,
map int(11) NOT NULL,
style int(11) NOT NULL,
longitude varchar(255) NOT NULL,
latitude varchar(255) NOT NULL,
zoom int(11) NOT NULL,
html text NOT NULL,
active int(11) NOT NULL,
date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (id)
) " . $charset_collate;

    $sql_marker_style = "CREATE TABLE IF NOT EXISTS " . SUPERMAPS_DB_STYLE_MARKER . " (
id int(11) NOT NULL AUTO_INCREMENT,
name varchar(255) NOT NULL,
file varchar(255) NOT NULL,
PRIMARY KEY (id)
) " . $charset_collate;

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($sql_polygon);
    dbDelta($sql_polygon_style);
    dbDelta($sql_line);
    dbDelta($sql_line_style);
    dbDelta($sql_map);
    dbDelta($sql_map_style);
    dbDelta($sql_marker);
    dbDelta($sql_marker_style);

    add_option('db_version', $db_version);

}