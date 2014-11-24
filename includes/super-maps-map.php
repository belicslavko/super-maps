<?php

// Map list page

function super_maps_map(){

    global $wpdb;



    $print = "<div class='new-link'><a href='admin.php?page=super-maps&p=newMap'><span class='dashicons dashicons-plus'></span> ".__('Dodaj mapu', 'super-maps')."</a></div>";

    $print .= "<table class='table-list'>";
    $print .= "<tr>";
    $print .= "<th>".__('ID', 'super-maps')."</th>";
    $print .= "<th>".__('Ime', 'super-maps')."</th>";
    $print .= "<th>".__('Stil', 'super-maps')."</th>";
    $print .= "<th>".__('Active', 'super-maps')."</th>";
    $print .= "<th>".__('Datum', 'super-maps')."</th>";
    $print .= "<th>".__('Opcije', 'super-maps')."</th>";
    $print .= "</tr>";

    $results = $wpdb->get_results( 'SELECT * FROM '. SUPERMAPS_DB_MAP, OBJECT );

    foreach($results as $r){

        $results_style = $wpdb->get_row( 'SELECT * FROM '.SUPERMAPS_DB_STYLE_MAP .' WHERE id = '.$r->style, OBJECT );


        $print .= "<tr>";
        $print .= "<td>".$r->id."</td>";
        $print .= "<td>".$r->name."</td>";
        $print .= "<td>".$results_style->name."</td>";
        if($r->active == 1){
            $print .= "<td><span class='dashicons dashicons-yes'></span></td>";
        }else{
            $print .= "<td><span class='dashicons dashicons-no-alt'></span></td>";
        }
        $print .= "<td>".$r->date."</td>";
        $print .= "<td><a href='admin.php?page=super-maps&p=editMap&map=".$r->id."'><span class='dashicons dashicons-edit'></span></a> <a href='admin.php?page=super-maps&p=delMap&map=".$r->id."'><span class='dashicons dashicons-trash'></span></a></td>";
        $print .= "</tr>";
    }

    $print .= "</table>";

    echo $print;


}

// Category add

function super_maps_map_add(){


    global $wpdb;

    $style = $wpdb->get_results( 'SELECT * FROM '. SUPERMAPS_DB_STYLE_MAP, OBJECT );

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

    $print .= "<form class='super-maps' action='admin.php?page=super-maps&p=newMap' method='POST'>";
    $print .= "<div>";
    $print .= "<label>".__('Ime', 'super-maps')."</label>";
    $print .= "<input type='text' name='name'>";
    $print .= "</div>";
    $print .= "<div>";
    $print .= "<label>".__('Stil', 'super-maps')."</label>";
    $print .= "<select name='style'>";

    if(!empty($style)){
        foreach($style as $s){
            $print .= "<option value='".$s->id."'>".$s->name."</option>";
        }
    }else{
        $print .= "<option>".__('Ne postoje stilovi', 'super-maps')."</option>";
    }
    $print .= "</select>";
    $print .= "</div>";

    $print .= "<div>";
    $print .= "<label>".__('Marker layer', 'super-maps')."</label>";
    $print .= "<input type='checkbox' name='layerMarker' value='1'>";
    $print .= "</div>";

    $print .= "<div>";
    $print .= "<label>".__('Polygon layer', 'super-maps')."</label>";
    $print .= "<input type='checkbox' name='layerPolygon' value='1'>";
    $print .= "</div>";

    $print .= "<div>";
    $print .= "<label>".__('Line layer', 'super-maps')."</label>";
    $print .= "<input type='checkbox' name='layerLine' value='1'>";
    $print .= "</div>";


    $print .= "<div>";
    $print .= "<label>".__('Aktivno', 'super-maps')."</label>";
    $print .= "<input type='checkbox' name='active' value='1'>";
    $print .= "</div>";
    $print .= super_maps_map_html();

    $print .= '<div class="form-group">
    <label class="col-md-2 control-label" for="inputText1">'.__('Zoom', 'super-maps').'</label>
    <div class="col-md-5">
        <input class="form-control" id="zoom" name="zoom" value="" type="text" >
    </div>
</div>';

    $print .= "<div>";
    $print .= "<input type='submit' value='" . __('Unesi', 'super-maps') . "' class='button action' >";
    $print .= "</div>";
    $print .= "</form>";


    if(!empty($_POST)){


        $wpdb->insert(
            SUPERMAPS_DB_MAP,
            array(
                'name' => sanitize_text_field($_POST['name']),
                'date' => date("Y-m-d H:i:s"),
                'style' => intval($_POST['style']),
                'layerMarker' => intval($_POST['layerMarker']),
                'layerPolygon' => intval($_POST['layerPolygon']),
                'layerLine' => intval($_POST['layerLine']),
                'active' => intval($_POST['active']),
                'longitude' => sanitize_text_field($_POST['longitude']),
                'latitude' => sanitize_text_field($_POST['latitude']),
                'zoom' => intval($_POST['zoom'])
            )
        );



        redirect_js('admin.php?page=super-maps&p=map');

    }

    echo $print;
}

// Category edit

function super_maps_map_edit(){


    global $wpdb;

    $id = intval($_GET['map']);

    $style = $wpdb->get_results( 'SELECT * FROM '. SUPERMAPS_DB_STYLE_MAP, OBJECT );

    $map = $wpdb->get_row("SELECT * FROM ".SUPERMAPS_DB_MAP." WHERE id = $id");


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

    $print .= "<form class='super-maps' action='admin.php?page=super-maps&p=editMap&cat=".$id."' method='POST'>";
    $print .= "<input type='hidden' name='id' value='".$id."'>";
    $print .= "<div>";
    $print .= "<label>".__('Ime', 'super-maps')."</label>";
    $print .= "<input type='text' name='name' value='".$map->name."'>";
    $print .= "</div>";
    $print .= "<div>";
    $print .= "<label>".__('Stil', 'super-maps')."</label>";
    $print .= "<select name='style'>";
    if(!empty($style)){
        foreach($style as $s){
            if($map->style == $s->id){
                $print .= "<option value='".$s->id."' selected>".$s->name."</option>";
            }else{
                $print .= "<option value='".$s->id."'>".$s->name."</option>";
            }
        }
    }else{
        $print .= "<option>".__('Ne postoje stilovi', 'super-maps')."</option>";
    }
    $print .= "</select>";
    $print .= "</div>";
    $print .= "<div>";
    $print .= "<label>".__('Aktivno', 'super-maps')."</label>";
    if($map->active == 1){
        $print .= "<input type='checkbox' name='active' value='1' checked>";
    }else{
        $print .= "<input type='checkbox' name='active' value='1'>";
    }
    $print .= "</div>";


    $print .= "<div>";
    $print .= "<label>".__('Marker layer', 'super-maps')."</label>";
    if($map->layerMarker == 1){
        $print .= "<input type='checkbox' name='layerMarker' value='1' checked>";
    }else{
        $print .= "<input type='checkbox' name='layerMarker' value='1'>";
    }
    $print .= "</div>";


    $print .= "<div>";
    $print .= "<label>".__('Polygon layer', 'super-maps')."</label>";
    if($map->layerPolygon == 1){
        $print .= "<input type='checkbox' name='layerPolygon' value='1' checked>";
    }else{
        $print .= "<input type='checkbox' name='layerPolygon' value='1'>";
    }
    $print .= "</div>";


    $print .= "<div>";
    $print .= "<label>".__('Line layer', 'super-maps')."</label>";
    if($map->LayerLine == 1){
        $print .= "<input type='checkbox' name='LayerLine' value='1' checked>";
    }else{
        $print .= "<input type='checkbox' name='LayerLine' value='1'>";
    }
    $print .= "</div>";





    $print .= super_maps_map_html($map->longitude, $map->latitude);

    $print .= '<div class="form-group">
    <label class="col-md-2 control-label" for="inputText1">'.__('Zoom', 'super-maps').'</label>
    <div class="col-md-5">
        <input class="form-control" id="zoom" name="zoom" value="'.$map->zoom.'" type="text" >
    </div>
    </div>';

    $print .= "<div>";
    $print .= "<input type='submit' value='" . __('Unesi', 'super-maps') . "' class='button action' >";
    $print .= "</div>";
    $print .= "</form>";



    if(!empty($_POST)){

        $wpdb->update(
            SUPERMAPS_DB_MAP,
            array(
                'name' => sanitize_text_field($_POST['name']),
                'date' => date("Y-m-d H:i:s"),
                'style' => intval($_POST['style']),
                'layerMarker' => intval($_POST['layerMarker']),
                'layerPolygon' => intval($_POST['layerPolygon']),
                'layerLine' => intval($_POST['layerLine']),
                'active' => intval($_POST['active']),
                'longitude' => sanitize_text_field($_POST['longitude']),
                'latitude' => sanitize_text_field($_POST['latitude']),
                'zoom' => intval($_POST['zoom'])
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

function super_maps_map_del(){

    global $wpdb;

    $id = intval($_GET['map']);

    $wpdb->delete( SUPERMAPS_DB_MAP, array( 'ID' => $id ) );

    redirect_js('admin.php?page=super-maps&p=map');
}
