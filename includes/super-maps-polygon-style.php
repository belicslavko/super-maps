<?php

// Map list page

function super_maps_polygon_style(){

    global $wpdb;

    $print = "<div class='new-link margin-top-20'><a href='admin.php?page=super-maps&p=newPolygonStyle'><span class='dashicons dashicons-plus'></span> ".__('Dodaj poligon stil', 'super-maps')."</a></div>";

    $print .= "<table class='table-list'>";
    $print .= "<tr>";
    $print .= "<th>".__('ID', 'super-maps')."</th>";
    $print .= "<th>".__('Ime', 'super-maps')."</th>";
    $print .= "<th>".__('Opcije', 'super-maps')."</th>";
    $print .= "</tr>";

    $results = $wpdb->get_results( 'SELECT * FROM '. SUPERMAPS_DB_STYLE_POLYGON, OBJECT );

    foreach($results as $r){
        $print .= "<tr>";
        $print .= "<td>".$r->id."</td>";
        $print .= "<td>".$r->name."</td>";


        $print .= "<td><a href='admin.php?page=super-maps&p=editPolygonStyle&style=".$r->id."'><span class='dashicons dashicons-edit'></span></a> <a href='admin.php?page=super-maps&p=delPolygonStyle&style=".$r->id."'><span class='dashicons dashicons-trash'></span></a></td>";
        $print .= "</tr>";
    }

    $print .= "</table>";

    echo $print;


}

// Category add

function super_maps_polygon_style_add(){


    global $wpdb;


    $print = "<script language='JavaScript'>
            jQuery(document).ready(function() {
            jQuery('#upload_image_button').click(function() {
            formfield = jQuery('#upload_image').attr('name');
            tb_show('', 'media-upload.php?type=image&TB_iframe=true');
            return false;
            });

            window.send_to_editor = function(html) {
            imgurl = jQuery('img',html).attr('src');
            jQuery('#upload_image').val(imgurl);
            tb_remove();
            }

            });
            </script>";

    $print .= "<form class='super-maps' action='admin.php?page=super-maps&p=newPolygonStyle' method='POST'>";
    $print .= "<div>";
    $print .= "<label>".__('Ime', 'super-maps')."</label>";
    $print .= "<input type='text' name='name'>";
    $print .= "</div>";



    $print .= "<div>";
    $print .= "<label>" . __('strokeColor', 'super-maps') . "</label>";
    $print .= "<input type='text' name='stroke_color' class='form-input-tip'>";
    $print .= "</div>";
    $print .= "<div>";
    $print .= "<label>" . __('strokeOpacity', 'super-maps') . "</label>";
    $print .= "<input type='text' name='stroke_opacity' class='form-input-tip'>";
    $print .= "</div>";
    $print .= "<div>";
    $print .= "<label>" . __('strokeWeight', 'super-maps') . "</label>";
    $print .= "<input type='text' name='stroke_weight' class='form-input-tip'>";
    $print .= "</div>";
    $print .= "<div>";
    $print .= "<label>" . __('fillColor', 'super-maps') . "</label>";
    $print .= "<input type='text' name='fill_color' class='form-input-tip'>";
    $print .= "</div>";
    $print .= "<div>";
    $print .= "<label>" . __('fillOpacity', 'super-maps') . "</label>";
    $print .= "<input type='text' name='fill_opacity' class='form-input-tip'>";
    $print .= "</div>";



    $print .= "<div>";
    $print .= "<input type='submit' value='" . __('Unesi', 'super-maps') . "' class='button action' >";
    $print .= "</div>";
    $print .= "</form>";


    if(!empty($_POST)){


        $wpdb->insert(
            SUPERMAPS_DB_STYLE_POLYGON,
            array(
                'name' => sanitize_text_field($_POST['name']),
                'stroke_color' => sanitize_text_field($_POST['stroke_color']),
                'stroke_opacity' => sanitize_text_field($_POST['stroke_opacity']),
                'stroke_weight' => sanitize_text_field($_POST['stroke_weight']),
                'fill_color' => sanitize_text_field($_POST['fill_color']),
                'fill_opacity' => sanitize_text_field($_POST['fill_opacity'])
            )
        );


        redirect_js('admin.php?page=super-maps&p=polygon');

    }

    echo $print;
}

// Category edit

function super_maps_polygon_style_edit(){


    global $wpdb;

    $id = intval($_GET['style']);

    $style = $wpdb->get_row("SELECT * FROM ".SUPERMAPS_DB_STYLE_POLYGON." WHERE id = $id");


    $print = "<script language='JavaScript'>
            jQuery(document).ready(function() {
            jQuery('#upload_image_button').click(function() {
            formfield = jQuery('#upload_image').attr('name');
            tb_show('', 'media-upload.php?type=image&TB_iframe=true');
            return false;
            });

            window.send_to_editor = function(html) {
            imgurl = jQuery('img',html).attr('src');
            jQuery('#upload_image').val(imgurl);
            tb_remove();
            }

            });
            </script>";

    $print .= "<form class='super-maps' action='admin.php?page=super-maps&p=editPolygonStyle&style=".$id."' method='POST'>";
    $print .= "<input type='hidden' name='id' value='".$id."'>";
    $print .= "<div>";
    $print .= "<label>".__('Ime', 'super-maps')."</label>";
    $print .= "<input type='text' name='name' value='".$style->name."'>";
    $print .= "</div>";
    $print .= "<div>";
    $print .= "<label>" . __('strokeColor', 'super-maps') . "</label>";
    $print .= "<input type='text' name='stroke_color' class='form-input-tip' value='" . $style->stroke_color . "'>";
    $print .= "</div>";
    $print .= "<div>";
    $print .= "<label>" . __('strokeOpacity', 'super-maps') . "</label>";
    $print .= "<input type='text' name='stroke_opacity' class='form-input-tip' value='" . $style->stroke_opacity . "'>";
    $print .= "</div>";
    $print .= "<div>";
    $print .= "<label>" . __('strokeWeight', 'super-maps') . "</label>";
    $print .= "<input type='text' name='stroke_weight' class='form-input-tip' value='" . $style->stroke_weight . "'>";
    $print .= "</div>";
    $print .= "<div>";
    $print .= "<label>" . __('fillColor', 'super-maps') . "</label>";
    $print .= "<input type='text' name='fill_color' class='form-input-tip' value='" . $style->fill_color . "'>";
    $print .= "</div>";
    $print .= "<div>";
    $print .= "<label>" . __('fillOpacity', 'super-maps') . "</label>";
    $print .= "<input type='text' name='fill_opacity' class='form-input-tip' value='" . $style->fill_opacity . "'>";
    $print .= "</div>";


    $print .= "<div>";
    $print .= "<input type='submit' value='" . __('Unesi', 'super-maps') . "' class='button action' >";
    $print .= "</div>";
    $print .= "</form>";



    if(!empty($_POST)){

        $wpdb->update(
            SUPERMAPS_DB_STYLE_POLYGON,
            array(
                'name' => sanitize_text_field($_POST['name']),
                'stroke_color' => sanitize_text_field($_POST['stroke_color']),
                'stroke_opacity' => sanitize_text_field($_POST['stroke_opacity']),
                'stroke_weight' => sanitize_text_field($_POST['stroke_weight']),
                'fill_color' => sanitize_text_field($_POST['fill_color']),
                'fill_opacity' => sanitize_text_field($_POST['fill_opacity']),
            ),
            array( 'id' => $_POST['id'] )
        );

        echo $print;

        redirect_js('admin.php?page=super-maps&p=polygon');

    }else{

        echo $print;

    }


}

// Category del

function super_maps_polygon_style_del(){

    global $wpdb;

    $id = intval($_GET['style']);

    $wpdb->delete( SUPERMAPS_DB_STYLE_POLYGON, array( 'ID' => $id ) );

    redirect_js('admin.php?page=super-maps&p=polygon');
}
