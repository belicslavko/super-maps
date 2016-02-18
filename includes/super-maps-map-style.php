<?php

// Map list page

function super_maps_map_style(){

    global $wpdb;

    $print = "<div class='new-link margin-top-20'><a href='admin.php?page=super-maps&p=newMapStyle'><span class='dashicons dashicons-plus'></span> ".__('Dodaj stil', 'super-maps')."</a></div>";

    $print .= "<table class='table-list'>";
    $print .= "<tr>";
    $print .= "<th>".__('ID', 'super-maps')."</th>";
    $print .= "<th>".__('Ime', 'super-maps')."</th>";

    $print .= "<th>".__('Opcije', 'super-maps')."</th>";
    $print .= "</tr>";

    $results = $wpdb->get_results( 'SELECT * FROM '. SUPERMAPS_DB_STYLE_MAP, OBJECT );

    foreach($results as $r){
        $print .= "<tr>";
        $print .= "<td>".$r->id."</td>";
        $print .= "<td>".$r->name."</td>";

        $print .= "<td><a href='admin.php?page=super-maps&p=editMapStyle&style=".$r->id."'><span class='dashicons dashicons-edit'></span></a> <a href='admin.php?page=super-maps&p=delMapStyle&style=".$r->id."'><span class='dashicons dashicons-trash'></span></a></td>";
        $print .= "</tr>";
    }

    $print .= "</table>";

    echo $print;


}

// Category add

function super_maps_map_style_add(){


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

    $print .= "<form class='super-maps' action='admin.php?page=super-maps&p=newMapStyle' method='POST'>";
    $print .= "<div>";
    $print .= "<label>".__('Ime', 'super-maps')."</label>";
    $print .= "<input type='text' name='name'>";
    $print .= "</div>";



    $print .= "<div>";
    $print .= "<label>" . __('Visina', 'super-maps') . "</label>";
    $print .= "<input type='text' name='height' class='form-input-tip'>";
    $print .= "</div>";
    $print .= "<div>";
    $print .= "<label>" . __('Širina', 'super-maps') . "</label>";
    $print .= "<input type='text' name='width' class='form-input-tip'>";
    $print .= "</div>";

    $print .= "<div>";
    $print .= "<label>" . __('Niz teme', 'super-maps') . "</label>";
    $print .= "<textarea id='html' name='theme_array' ></textarea>";
    $print .= "</div>";

    $print .= "<div>";
    $print .= "<input type='submit' value='" . __('Unesi', 'super-maps') . "' class='button action' >";
    $print .= "</div>";
    $print .= "</form>";


    if(!empty($_POST)){


        $wpdb->insert(
            SUPERMAPS_DB_STYLE_MAP,
            array(
                'name' => sanitize_text_field($_POST['name']),
                'height' => sanitize_text_field($_POST['height']),
                'width' => sanitize_text_field($_POST['width']),
                'theme_array' => sanitize_text_field($_POST['theme_array'])            )
        );

        redirect_js('admin.php?page=super-maps&p=map');

    }

    echo $print;
}

// Category edit

function super_maps_map_style_edit(){


    global $wpdb;

    $id = intval($_GET['style']);

    $style = $wpdb->get_row("SELECT * FROM ".SUPERMAPS_DB_STYLE_MAP." WHERE id = $id");


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

    $print .= "<form class='super-maps' action='admin.php?page=super-maps&p=editMapStyle&style=".$id."' method='POST'>";
    $print .= "<input type='hidden' name='id' value='".$id."'>";
    $print .= "<div>";
    $print .= "<label>".__('Ime', 'super-maps')."</label>";
    $print .= "<input type='text' name='name' value='".$style->name."'>";
    $print .= "</div>";

    $print .= "<div>";
    $print .= "<label>" . __('Visina', 'super-maps') . "</label>";
    $print .= "<input type='text' name='height' class='form-input-tip' value='".$style->height."'>";
    $print .= "</div>";
    $print .= "<div>";
    $print .= "<label>" . __('Širina', 'super-maps') . "</label>";
    $print .= "<input type='text' name='width' class='form-input-tip' value='".$style->width."'>";
    $print .= "</div>";
    $print .= "<div>";
    $print .= "<label>" . __('Niz teme', 'super-maps') . "</label>";
    $print .= "<textarea id='html' name='theme_array' >".stripslashes($style->theme_array)."</textarea>";
    $print .= "</div>";

    $print .= "<div>";
    $print .= "<input type='submit' value='" . __('Unesi', 'super-maps') . "' class='button action' >";
    $print .= "</div>";
    $print .= "</form>";



    if(!empty($_POST)){

        $wpdb->update(
            SUPERMAPS_DB_STYLE_MAP,
            array(
                'name' => sanitize_text_field($_POST['name']),
                'height' => sanitize_text_field($_POST['height']),
                'width' => sanitize_text_field($_POST['width']),
                'theme_array' => sanitize_text_field($_POST['theme_array'])
            ),
            array( 'id' => $_POST['id'] )
        );

        echo $print;

        redirect_js('admin.php?page=super-maps&p=map');

    }else{

        echo $print;

    }


}

// Category del

function super_maps_map_style_del(){

    global $wpdb;

    $id = intval($_GET['style']);

    $wpdb->delete( SUPERMAPS_DB_STYLE_MAP, array( 'ID' => $id ) );

    redirect_js('admin.php?page=super-maps&p=map');
}
