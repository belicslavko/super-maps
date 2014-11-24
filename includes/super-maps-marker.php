<?php

// Marker list page

function super_maps_marker(){

    global $wpdb;



    $print = "<div class='new-link'><a href='admin.php?page=super-maps&p=newMarker'><span class='dashicons dashicons-plus'></span> ".__('Dodaj marker', 'super-maps')."</a></div>";

    $print .= "<table class='table-list'>";
    $print .= "<tr>";
    $print .= "<th>".__('ID', 'super-maps')."</th>";
    $print .= "<th>".__('Ime', 'super-maps')."</th>";
    $print .= "<th>".__('Stil', 'super-maps')."</th>";
    $print .= "<th>".__('Mapa', 'super-maps')."</th>";
    $print .= "<th>".__('Active', 'super-maps')."</th>";
    $print .= "<th>".__('Datum', 'super-maps')."</th>";
    $print .= "<th>".__('Opcije', 'super-maps')."</th>";
    $print .= "</tr>";

    $results = $wpdb->get_results( 'SELECT * FROM '.SUPERMAPS_DB_MARKER, OBJECT );

    foreach($results as $r){

        $results_style = $wpdb->get_row( 'SELECT * FROM '.SUPERMAPS_DB_STYLE_MARKER .' WHERE id = '.$r->style, OBJECT );
        $results_map = $wpdb->get_row('SELECT * FROM '.SUPERMAPS_DB_MAP.' WHERE id = ' . $r->map, OBJECT);

        $print .= "<tr>";
        $print .= "<td>".$r->id."</td>";
        $print .= "<td>".$r->name."</td>";
        $print .= "<td>".$results_style->name."</td>";
        $print .= "<td>" . $results_map->name . "</td>";
        if($r->active == 1){
            $print .= "<td><span class='dashicons dashicons-yes'></span></td>";
        }else{
            $print .= "<td><span class='dashicons dashicons-no-alt'></span></td>";
        }
        $print .= "<td>".$r->date."</td>";
        $print .= "<td><a href='admin.php?page=super-maps&p=editMarker&mark=".$r->id."'><span class='dashicons dashicons-edit'></span></a> <a href='admin.php?page=super-maps&p=delMarker&mark=".$r->id."'><span class='dashicons dashicons-trash'></span></a></td>";
        $print .= "</tr>";
    }

    $print .= "</table>";

    echo $print;


}

// Marker add

function super_maps_marker_add(){


    global $wpdb;

    $style = $wpdb->get_results("SELECT * FROM ".SUPERMAPS_DB_STYLE_MARKER);
    $map = $wpdb->get_results("SELECT * FROM ".SUPERMAPS_DB_MAP);

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

    $print .= "<form class='super-maps' action='admin.php?page=super-maps&p=newMarker' method='POST'>";
    $print .= "<div>";
    $print .= "<label>".__('Ime', 'super-maps')."</label>";
    $print .= "<input type='text' name='name'>";
    $print .= "</div>";

    $print .= "<div>";
    $print .= "<label>" . __('HTML', 'super-maps') . "</label>";
    $print .= "<textarea id='html' name='html' ></textarea>";
    $print .= "</div>";
    $print .= "<div>";
    $print .= "<label>" . __('Mapa', 'super-maps') . "</label>";
    $print .= "<select name='map'>";

    if (!empty($map)) {
        foreach ($map as $m) {
            $print .= "<option value='" . $m->id . "'>" . $m->name . "</option>";
        }
    } else {
        $print .= "<option>" . __('Ne postoje mape', 'super-maps') . "</option>";
    }

    $print .= "</select>";
    $print .= "</div>";
    $print .= "<div>";
    $print .= "<label>".__('Stil', 'super-maps')."</label>";
    $print .= "<select name='style'>";

    if(!empty($style)){
        foreach($style as $s){
            $print .= "<option value='".$s->id."'>".$s->name."</option>";
        }
    }else{
        $print .= "<option>".__('Ne postoje kategorije', 'super-maps')."</option>";
    }

    $print .= "</select>";
    $print .= "</div>";
    $print .= "<div>";
    $print .= "<label>".__('Aktivno', 'super-maps')."</label>";
    $print .= "<input type='checkbox' name='active' value='1'>";
    $print .= "</div>";
    $print .= super_maps_marker_html();

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
            SUPERMAPS_DB_MARKER,
            array(
                'name' => sanitize_text_field($_POST['name']),
                'map' => intval($_POST['map']),
                'html' => sanitize_text_field($_POST['html']),
                'style' => intval($_POST['style']),
                'date' => date("Y-m-d H:i:s"),
                'active' => intval($_POST['active']),
                'longitude' => sanitize_text_field($_POST['longitude']),
                'latitude' => sanitize_text_field($_POST['latitude']),
                'zoom' => intval($_POST['zoom'])
            )
        );

        redirect_js('admin.php?page=super-maps&p=marker');

    }

    echo $print;
}

// Marker edit

function super_maps_marker_edit(){



    global $wpdb;

    $id = intval($_GET['mark']);

    $marker = $wpdb->get_row("SELECT * FROM ".SUPERMAPS_DB_MARKER ." WHERE id = $id");

    $style = $wpdb->get_results("SELECT * FROM ".SUPERMAPS_DB_STYLE_MARKER);

    $map = $wpdb->get_results("SELECT * FROM ".SUPERMAPS_DB_MAP);


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

    $print .= "<form class='super-maps' action='admin.php?page=super-maps&p=editMarker&mark=".$id."' method='POST'>";
    $print .= "<input type='hidden' name='id' value='".$id."'>";
    $print .= "<div>";
    $print .= "<label>".__('Ime', 'super-maps')."</label>";
    $print .= "<input type='text' name='name' value='".$marker->name."'>";
    $print .= "</div>";

    $print .= "<div>";
    $print .= "<label>" . __('HTML', 'super-maps') . "</label>";
    $print .= "<textarea id='html' name='html'>" . $marker->html . "</textarea>";
    $print .= "</div>";
    $print .= "<div>";
    $print .= "<label>" . __('Mapa', 'super-maps') . "</label>";
    $print .= "<select name='map'>";

    if (!empty($map)) {
        foreach ($map as $m) {

            if ($pol->map == $m->id) {
                $print .= "<option value='" . $m->id . "' selected>" . $m->name . "</option>";
            } else {
                $print .= "<option value='" . $m->id . "'>" . $m->name . "</option>";
            }
        }
    } else {
        $print .= "<option>" . __('Ne postoje mape', 'super-maps') . "</option>";
    }

    $print .= "</select>";
    $print .= "</div>";
    $print .= "<div>";
    $print .= "<label>".__('Stil', 'super-maps')."</label>";
    $print .= "<select name='style'>";

    if(!empty($style)){
        foreach($style as $s){

            if($marker->style == $s->id){
                $print .= "<option value='".$s->id."' selected>".$s->name."</option>";
            }else{
                $print .= "<option value='".$s->id."'>".$s->name."</option>";
            }
        }
    }else{
        $print .= "<option>".__('Ne postoje kategorije', 'super-maps')."</option>";
    }

    $print .= "</select>";
    $print .= "</div>";
    $print .= "<div>";
    $print .= "<label>".__('Aktivno', 'super-maps')."</label>";
    if($marker->active == 1){
        $print .= "<input type='checkbox' name='active' value='1' checked>";
    }else{
        $print .= "<input type='checkbox' name='active' value='1'>";
    }
    $print .= "</div>";

    $print .= super_maps_marker_html($marker->longitude, $marker->latitude);

    $print .= '<div class="form-group">
    <label class="col-md-2 control-label" for="inputText1">'.__('Zoom', 'super-maps').'</label>
    <div class="col-md-5">
        <input class="form-control" id="zoom" name="zoom" value="'.$marker->zoom.'" type="text" >
    </div>
    </div>';

    $print .= "<div>";
    $print .= "<input type='submit' value='" . __('Unesi', 'super-maps') . "' class='button action' >";
    $print .= "</div>";
    $print .= "</form>";



    if(!empty($_POST)){

        $wpdb->update(
            SUPERMAPS_DB_MARKER,
            array(
                'name' => sanitize_text_field($_POST['name']),
                'map' => intval($_POST['map']),
                'html' => sanitize_text_field($_POST['html']),
                'style' => intval($_POST['style']),
                'date' => date("Y-m-d H:i:s"),
                'active' => intval($_POST['active']),
                'longitude' => sanitize_text_field($_POST['longitude']),
                'latitude' => sanitize_text_field($_POST['latitude']),
                'zoom' => intval($_POST['zoom'])
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

function super_maps_marker_del(){

    global $wpdb;

    $id = intval($_GET['mark']);

    $wpdb->delete( SUPERMAPS_DB_MARKER, array( 'ID' => $id ) );

    redirect_js('admin.php?page=super-maps&p=marker');
}

