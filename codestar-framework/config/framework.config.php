<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// FRAMEWORK SETTINGS
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
$settings           = array(
  'menu_title'      => 'SuperMaps',
  'menu_type'       => 'options', // menu, submenu, options, theme, etc.
  'menu_slug'       => 'supermaps',
  'ajax_save'       => false,
  'show_reset_all'  => false,
  'framework_title' => 'SuperMaps',
);

// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// FRAMEWORK OPTIONS
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
$options        = array();

// ----------------------------------------
// a option section for options overview  -
// ----------------------------------------
$options[]      = array(
  'name'        => 'overwiew',
  'title'       => 'API',
  'icon'        => 'fa fa-star',

  // begin: fields
  'fields'      => array(

    // begin: a field
    array(
      'id'      => 'google_maps_api_key',
      'type'    => 'text',
      'title'   => 'Google Maps API key',
    ),
    // end: a field


  ), // end: fields
);



$options[] = array(
    'name' => 'custom_code',
    'title' => 'Custom code',
    'icon' => 'fa fa-code',

    'fields' => array(

        array(
            'id' => 'custom_css',
            'type' => 'textarea',
            'title' => 'Custom css',
            'desc' => 'code inside < style></style>',
        ),

        array(
            'id' => 'custom_after_js',
            'type' => 'textarea',
            'title' => 'Custom after js',
            'desc' => 'code inside < script></script>',
        ),

        array(
            'id' => 'custom_before_js',
            'type' => 'textarea',
            'title' => 'Custom before js',
            'desc' => 'code inside < script></script>',
        ),

    ),


);



// ------------------------------
// license                      -
// ------------------------------
$options[]   = array(
  'name'     => 'license_section',
  'title'    => 'License',
  'icon'     => 'fa fa-info-circle',
  'fields'   => array(

    array(
      'type'    => 'heading',
      'content' => '100% GPL License, Yes it is free!'
    ),
    array(
      'type'    => 'content',
      'content' => 'Codestar Framework is <strong>free</strong> to use both personal and commercial. If you used commercial, <strong>please credit</strong>. Read more about <a href="http://www.gnu.org/licenses/gpl-2.0.txt" target="_blank">GNU License</a>',
    ),

  )
);

CSFramework::instance( $settings, $options );
