<?php

// Marker list page

function super_maps_marker_style(){

    global $wpdb;



    $print = "<div class='new-link margin-top-20'><a href='admin.php?page=super-maps&p=newMarkerStyle'><span class='dashicons dashicons-plus'></span> ".__('Dodaj stil', 'super-maps')."</a></div>";

    $print .= "<table class='table-list'>";
    $print .= "<tr>";
    $print .= "<th>".__('ID', 'super-maps')."</th>";
    $print .= "<th>".__('Ime', 'super-maps')."</th>";
    $print .= "<th>".__('Opcije', 'super-maps')."</th>";
    $print .= "</tr>";

    $results = $wpdb->get_results( 'SELECT * FROM '.SUPERMAPS_DB_STYLE_MARKER, OBJECT );

    foreach($results as $r){


        $print .= "<tr>";
        $print .= "<td>".$r->id."</td>";
        $print .= "<td>".$r->name."</td>";

        $print .= "<td><a href='admin.php?page=super-maps&p=editMarkerStyle&mark=".$r->id."'><span class='dashicons dashicons-edit'></span></a> <a href='admin.php?page=super-maps&p=delMarkerStyle&mark=".$r->id."'><span class='dashicons dashicons-trash'></span></a></td>";
        $print .= "</tr>";
    }

    $print .= "</table>";

    echo $print;


}

// Marker add

function super_maps_marker_style_add(){


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

    $print .= "<form class='super-maps' action='admin.php?page=super-maps&p=newMarkerStyle' method='POST'>";
    $print .= "<div>";
    $print .= "<label>".__('Ime', 'super-maps')."</label>";
    $print .= "<input type='text' name='name'>";
    $print .= "</div>";
    $print .= "<div>";
    $print .= "<label>".__('Marker', 'super-maps')."</label>";
    $print .= '<input id="upload_image" type="text" size="36" name="upload_image" value="" />';
    $print .= '<input id="upload_image_button" type="button" value="Unos slike" />';
    $print .= "</div>";


    $print .= "<div>";
    $print .= "<input type='submit' value='" . __('Unesi', 'super-maps') . "' class='button action' >";
    $print .= "</div>";
    $print .= "</form>";


    if(!empty($_POST)){

        $wpdb->insert(
            SUPERMAPS_DB_STYLE_MARKER,
            array(
                'name' => sanitize_text_field($_POST['name']),
                'file' => sanitize_text_field($_POST['upload_image'])
            )
        );

        redirect_js('admin.php?page=super-maps&p=marker');

    }

    echo $print;
}

// Marker edit

function super_maps_marker_style_edit(){



    global $wpdb;

    $id = intval($_GET['mark']);

    $mark = $wpdb->get_row("SELECT * FROM ".SUPERMAPS_DB_STYLE_MARKER ." WHERE id = $id");




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

    $print .= "<form class='super-maps' action='admin.php?page=super-maps&p=editMarkerStyle&mark=".$id."' method='POST'>";
    $print .= "<input type='hidden' name='id' value='".$id."'>";
    $print .= "<div>";
    $print .= "<label>".__('Ime', 'super-maps')."</label>";
    $print .= "<input type='text' name='name' value='".$mark->name."'>";
    $print .= "</div>";
    $print .= "<div>";
    $print .= "<label>".__('Marker', 'super-maps')."</label>";
    $print .= '<input id="upload_image" type="text" size="36" name="upload_image" value="'.$mark->file.'" />';
    $print .= '<input id="upload_image_button" type="button" value="Unos slike" />';
    $print .= "</div>";


    $print .= "<div>";
    $print .= "<input type='submit' value='" . __('Unesi', 'super-maps') . "' class='button action' >";
    $print .= "</div>";
    $print .= "</form>";



    if(!empty($_POST)){

        $wpdb->update(
            SUPERMAPS_DB_STYLE_MARKER,
            array(
                'name' => sanitize_text_field($_POST['name']),
                'file' => sanitize_text_field($_POST['upload_image']),

            ),
            array( 'id' => $_POST['id'] )
        );



        echo $print;

        redirect_js('admin.php?page=super-maps&p=marker');

    }else{

        echo $print;

    }


}

// Marker del

function super_maps_marker_style_del(){

    global $wpdb;

    $id = intval($_GET['mark']);

    $wpdb->delete( SUPERMAPS_DB_STYLE_MARKER, array( 'ID' => $id ) );

    redirect_js('admin.php?page=super-maps&p=marker');
}

