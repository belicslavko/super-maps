<?php if (!defined('ABSPATH')) {
    die;
} // Cannot access pages directly.
// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// METABOX OPTIONS
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
$options = array();

// -----------------------------------------
// Maps Metabox Options                    -
// -----------------------------------------
$options[] = array(
    'id' => '_custom_supermaps_options',
    'title' => 'SuperMaps Options',
    'post_type' => 'supermaps',
    'context' => 'normal',
    'priority' => 'default',
    'sections' => array(

        // begin: a section
        array(
            'name' => 'section_supermaps',
            'icon' => 'fa fa-cog',

            // begin: fields
            'fields' => array(

                // begin: a field
                array(
                    'id' => 'latitude',
                    'type' => 'text',
                    'title' => 'Latitude',
                    'attributes' => array(
                        'id' => 'latitude',
                    ),
                ),
                // end: a field

                array(
                    'id' => 'longitude',
                    'type' => 'text',
                    'title' => 'Longitude',
                    'attributes' => array(
                        'id' => 'longitude',
                    ),
                ),

                array(
                    'id' => 'zoom',
                    'type' => 'text',
                    'title' => 'Zoom',
                    'default' => 7,
                    'attributes' => array(
                        'id' => 'zoom',
                    ),
                ),

                array(
                    'id' => 'map_div',
                    'type' => 'content',
                    'before' => '<div id="supermaps" style="width: 100%; height: 400px"></div>'
                ),

                array(
                    'id' => 'scroll',
                    'type' => 'switcher',
                    'title' => 'Mouse scroll in map',
                ),

                array(
                    'id' => 'draggable',
                    'type' => 'switcher',
                    'title' => 'Dragg in map',
                ),

                array(
                    'id' => 'map_enable_layer',
                    'type' => 'switcher',
                    'title' => 'Map Enable layer',
                ),

                array(
                    'id' => 'marker_layer',
                    'type' => 'switcher',
                    'title' => 'Marker layer',
                ),

                array(
                    'id' => 'polygon_layer',
                    'type' => 'switcher',
                    'title' => 'Polygon layer',
                ),

                array(
                    'id' => 'line_layer',
                    'type' => 'switcher',
                    'title' => 'Line layer',
                ),

                array(
                    'id' => 'overlay_layer',
                    'type' => 'switcher',
                    'title' => 'Overlay layer',
                ),

                array(
                    'type' => 'heading',
                    'content' => 'Map Design',
                ),

                array(
                    'id' => 'width',
                    'type' => 'number',
                    'title' => 'Width',
                ),

                array(
                    'id' => 'height',
                    'type' => 'number',
                    'title' => 'Height',
                ),

                array(
                    'id' => 'theme_array',
                    'type' => 'textarea',
                    'title' => 'Google map theme array',
                ),

            ), // end: fields
        ), // end: a section


    ),
);


// -----------------------------------------
// Marker Metabox Options                    -
// -----------------------------------------
$options[] = array(
    'id' => '_custom_supermaps_marker_options',
    'title' => 'SuperMaps Marker Options',
    'post_type' => 'supermaps_marker',
    'context' => 'normal',
    'priority' => 'default',
    'sections' => array(

        // begin: a section
        array(
            'name' => 'section_supermaps_marker',
            'icon' => 'fa fa-cog',

            // begin: fields
            'fields' => array(

                array(
                    'id' => 'map',
                    'type' => 'select',

                    'title' => 'Select map',
                    'options' => 'posts',
                    'query_args' => array(
                        'post_type' => 'supermaps',
                        'posts_per_page' => -1
                    ),
                    'default_option' => 'Select'
                ),

                // begin: a field
                array(
                    'id' => 'latitude',
                    'type' => 'text',
                    'title' => 'Latitude',
                    'attributes' => array(
                        'id' => 'latitude',
                    ),
                ),
                // end: a field

                array(
                    'id' => 'longitude',
                    'type' => 'text',
                    'title' => 'Longitude',
                    'attributes' => array(
                        'id' => 'longitude',
                    ),
                ),

                array(
                    'id' => 'map_div',
                    'type' => 'content',
                    'before' => '<div id="supermaps" style="width: 100%; height: 400px"></div>'
                ),

                array(
                    'id'    => 'html',
                    'type'  => 'textarea',
                    'title' => 'Marker HTML',
                ),

                array(
                    'id'    => 'marker_img',
                    'type'  => 'image',
                    'title' => 'Marker icon',
                ),

            ), // end: fields
        ), // end: a section


    ),
);


// -----------------------------------------
// Line Metabox Options                    -
// -----------------------------------------
$options[] = array(
    'id' => '_custom_supermaps_line_options',
    'title' => 'Supermaps Line Options',
    'post_type' => 'supermaps_line',
    'context' => 'normal',
    'priority' => 'default',
    'sections' => array(

        // begin: a section
        array(
            'name' => 'section_supermaps_line',
            'icon' => 'fa fa-cog',

            // begin: fields
            'fields' => array(

                array(
                    'id' => 'map',
                    'type' => 'select',

                    'title' => 'Select map',
                    'options' => 'posts',
                    'query_args' => array(
                        'post_type' => 'supermaps',
                        'posts_per_page' => -1
                    ),
                    'default_option' => 'Select'
                ),

                // begin: a field
                array(
                    'id' => 'cords',
                    'type' => 'text',
                    'title' => 'Cordinates',
                    'attributes' => array(
                        'id' => 'showData',
                    ),
                    'after' => '<a id="reset-map" class="button cs-warning-primary">Clear map</a>',
                ),
                // end: a field



                array(
                    'id' => 'map_div',
                    'type' => 'content',
                    'before' => '<div id="supermaps" style="width: 100%; height: 400px"></div>'
                ),

                array(
                    'id'    => 'html',
                    'type'  => 'textarea',
                    'title' => 'Marker HTML',
                ),

                array(
                    'type'    => 'heading',
                    'content' => 'Line Design',
                ),
                array(
                    'id'    => 'stroke_color',
                    'type'  => 'color_picker',
                    'title' => 'strokeColor',
                ),

                array(
                    'id'    => 'stroke_opacity',
                    'type'  => 'text',
                    'title' => 'strokeOpacity',
                ),

                array(
                    'id'    => 'stroke_weight',
                    'type'  => 'text',
                    'title' => 'strokeWeight',
                ),

            ), // end: fields
        ), // end: a section


    ),
);



// -----------------------------------------
// Polygon Metabox Options                    -
// -----------------------------------------
$options[] = array(
    'id' => '_custom_supermaps_polygon_options',
    'title' => 'Supermaps Polygon Options',
    'post_type' => 'supermaps_polygon',
    'context' => 'normal',
    'priority' => 'default',
    'sections' => array(

        // begin: a section
        array(
            'name' => 'section_supermaps_polygon',
            'icon' => 'fa fa-cog',

            // begin: fields
            'fields' => array(

                array(
                    'id' => 'map',
                    'type' => 'select',

                    'title' => 'Select map',
                    'options' => 'posts',
                    'query_args' => array(
                        'post_type' => 'supermaps',
                        'posts_per_page' => -1
                    ),
                    'default_option' => 'Select'
                ),

                // begin: a field
                array(
                    'id' => 'cords',
                    'type' => 'text',
                    'title' => 'Cordinates',
                    'attributes' => array(
                        'id' => 'showData',
                    ),
                    'after' => '<a id="reset-map" class="button cs-warning-primary">Clear map</a>',
                ),
                // end: a field



                array(
                    'id' => 'map_div',
                    'type' => 'content',
                    'before' => '<div id="supermaps" style="width: 100%; height: 400px"></div>'
                ),

                array(
                    'id'    => 'html',
                    'type'  => 'textarea',
                    'title' => 'Marker HTML',
                ),

                array(
                    'type'    => 'heading',
                    'content' => 'Polygon Design',
                ),
                array(
                    'id'    => 'stroke_color',
                    'type'  => 'color_picker',
                    'title' => 'strokeColor',
                ),

                array(
                    'id'    => 'stroke_opacity',
                    'type'  => 'text',
                    'title' => 'strokeOpacity',
                ),

                array(
                    'id'    => 'stroke_weight',
                    'type'  => 'text',
                    'title' => 'strokeWeight',
                ),

                array(
                    'id'    => 'fill_color',
                    'type'  => 'color_picker',
                    'title' => 'fillColor',
                ),

                array(
                    'id'    => 'fill_opacity',
                    'type'  => 'text',
                    'title' => 'fillOpacity',
                ),

            ), // end: fields
        ), // end: a section


    ),
);



// -----------------------------------------
// Line Metabox Options                    -
// -----------------------------------------
$options[] = array(
    'id' => '_custom_supermaps_overlay_options',
    'title' => 'Supermaps Overlay Options',
    'post_type' => 'supermaps_overlay',
    'context' => 'normal',
    'priority' => 'default',
    'sections' => array(

        // begin: a section
        array(
            'name' => 'section_supermaps_overlay',
            'icon' => 'fa fa-cog',

            // begin: fields
            'fields' => array(

                array(
                    'id' => 'map',
                    'type' => 'select',

                    'title' => 'Select map',
                    'options' => 'posts',
                    'query_args' => array(
                        'post_type' => 'supermaps',
                        'posts_per_page' => -1
                    ),
                    'default_option' => 'Select'
                ),

                // begin: a field
                array(
                    'id' => 'latitude',
                    'type' => 'text',
                    'title' => 'Latitude',
                    'attributes' => array(
                        'id' => 'latitude',
                    ),
                ),
                // end: a field

                array(
                    'id' => 'longitude',
                    'type' => 'text',
                    'title' => 'Longitude',
                    'attributes' => array(
                        'id' => 'longitude',
                    ),
                ),

                array(
                    'id' => 'map_div',
                    'type' => 'content',
                    'before' => '<div id="supermaps" style="width: 100%; height: 400px"></div>'
                ),

                array(
                    'id'    => 'overlay_img',
                    'type'  => 'image',
                    'title' => 'Image',
                ),

                array(
                    'id'    => 'radius',
                    'type'  => 'number',
                    'title' => 'Radius',
                ),

            ), // end: fields
        ), // end: a section


    ),
);


CSFramework_Metabox::instance($options);
