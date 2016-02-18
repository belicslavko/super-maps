<?php

// Polygon list page

function super_maps_polygon_list()
{

    global $wpdb;

    $print = "<div class='new-link'><a href='admin.php?page=super-maps&p=newPolygon'><span class='dashicons dashicons-plus'></span> " . __('Dodaj poligon', 'super-maps') . "</a></div>";

    $print .= "<table class='table-list'>";
    $print .= "<tr>";
    $print .= "<th>" . __('ID', 'super-maps') . "</th>";
    $print .= "<th>" . __('Ime', 'super-maps') . "</th>";
    $print .= "<th>" . __('Stil', 'super-maps') . "</th>";
    $print .= "<th>" . __('Mapa', 'super-maps') . "</th>";
    $print .= "<th>" . __('Active', 'super-maps') . "</th>";
    $print .= "<th>" . __('Datum', 'super-maps') . "</th>";
    $print .= "<th>" . __('Opcije', 'super-maps') . "</th>";
    $print .= "</tr>";

    $results = $wpdb->get_results('SELECT * FROM '.SUPERMAPS_DB_POLYGON, OBJECT);

    foreach ($results as $r) {

        $results_style = $wpdb->get_row('SELECT * FROM ' . SUPERMAPS_DB_STYLE_POLYGON . ' WHERE id = ' . $r->style, OBJECT);
        $results_map = $wpdb->get_row('SELECT * FROM ' . SUPERMAPS_DB_MAP . ' WHERE id = ' . $r->map, OBJECT);

        $print .= "<tr>";
        $print .= "<td>" . $r->id . "</td>";
        $print .= "<td>" . $r->name . "</td>";
        if (!empty($results_style->name)){
            $print .= "<td>" . $results_style->name . "</td>";
        }else{
            $print .= "<td></td>";
        }

        if (!empty($results_map->name)) {
            $print .= "<td>" . $results_map->name . "</td>";
        }else{
            $print .= "<td></td>";
        }

        if ($r->active == 1) {
            $print .= "<td><span class='dashicons dashicons-yes'></span></td>";
        } else {
            $print .= "<td><span class='dashicons dashicons-no-alt'></span></td>";
        }
        $print .= "<td>" . $r->date . "</td>";
        $print .= "<td><a href='admin.php?page=super-maps&p=editPolygon&pol=" . $r->id . "'><span class='dashicons dashicons-edit'></span></a> <a href='admin.php?page=super-maps&p=delPolygon&pol=" . $r->id . "'><span class='dashicons dashicons-trash'></span></a></td>";
        $print .= "</tr>";
    }

    $print .= "</table>";

    echo $print;


}


// Add polygon page


function super_maps_polygon_add()
{

    global $wpdb;

    $style = $wpdb->get_results("SELECT * FROM ".SUPERMAPS_DB_STYLE_POLYGON);

    $map = $wpdb->get_results("SELECT * FROM ".SUPERMAPS_DB_MAP);

    $print = "";

    $print .= "<form class='super-maps' action='admin.php?page=super-maps&p=newPolygon' method='POST'>";
    $print .= "<div>";
    $print .= "<label>" . __('Ime', 'super-maps') . "</label>";
    $print .= "<input type='text' name='name' class='form-input-tip'>";
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
    $print .= "<label>" . __('Stil', 'super-maps') . "</label>";
    $print .= "<select name='style'>";

    if (!empty($style)) {
        foreach ($style as $s) {
            $print .= "<option value='" . $s->id . "'>" . $s->name . "</option>";
        }
    } else {
        $print .= "<option>" . __('Ne postoje stilovi', 'super-maps') . "</option>";
    }

    $print .= "</select>";
    $print .= "</div>";
    $print .= super_maps_polygon_html();
    $print .= "<div>";
    $print .= "<label>" . __('HTML', 'super-maps') . "</label>";
    $print .= "<textarea name='html' ></textarea>";
    $print .= "</div>";

    $print .= "<div>";
    $print .= "<label>" . __('Aktivno', 'super-maps') . "</label>";
    $print .= "<input type='checkbox' name='active' value='1'>";
    $print .= "</div>";

    $print .= "<div>";
    $print .= "<input type='submit' value='" . __('Unesi', 'super-maps') . "' class='button action' >";
    $print .= "</div>";
    $print .= "</form>";


    if (!empty($_POST)) {

        if(!empty($_POST['active'])){
            $active = $_POST['active'];
        }else{
            $active = '';
        }

        $wpdb->insert(
            SUPERMAPS_DB_POLYGON,
            array(
                'name' => sanitize_text_field($_POST['name']),
                'map' => intval($_POST['map']),
                'style' => intval($_POST['style']),
                'poligon_cord' => sanitize_text_field($_POST['poligon_cord']),
                'html' => sanitize_text_field($_POST['html']),
                'date' => date("Y-m-d H:i:s"),
                'active' => $active
            )
        );


        redirect_js('admin.php?page=super-maps&p=polygon');

    }

    echo $print;
}


// Edit polygon page


function super_maps_polygon_edit()
{

    global $wpdb;

    $id = intval($_GET['pol']);

    $style = $wpdb->get_results("SELECT * FROM ". SUPERMAPS_DB_STYLE_POLYGON);

    $pol = $wpdb->get_row("SELECT * FROM ". SUPERMAPS_DB_POLYGON ." WHERE id = $id");

    $map = $wpdb->get_results("SELECT * FROM ".SUPERMAPS_DB_MAP);

    $print = "";

    $print .= "<form class='super-maps' action='admin.php?page=super-maps&p=editPolygon&pol=" . $id . "' method='POST'>";
    $print .= "<input type='hidden' name='id' value='" . $id . "'>";
    $print .= "<div>";
    $print .= "<label>" . __('Ime', 'super-maps') . "</label>";
    $print .= "<input type='text' name='name' value='" . $pol->name . "'>";
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
    $print .= "<label>" . __('Stil', 'super-maps') . "</label>";
    $print .= "<select name='style'>";

    if (!empty($style)) {
        foreach ($style as $s) {

            if ($pol->style == $s->id) {
                $print .= "<option value='" . $s->id . "' selected>" . $s->name . "</option>";
            } else {
                $print .= "<option value='" . $s->id . "'>" . $s->name . "</option>";
            }
        }
    } else {
        $print .= "<option>" . __('Ne postoje kategorije', 'super-maps') . "</option>";
    }

    $print .= "</select>";
    $print .= "</div>";
    $print .= super_maps_polygon_html($pol->poligon_cord);
    $print .= "<div>";
    $print .= "<label>" . __('HTML', 'super-maps') . "</label>";
    $print .= "<textarea name='html'>" . $pol->html . "</textarea>";
    $print .= "</div>";

    $print .= "<div>";
    $print .= "<label>" . __('Aktivno', 'super-maps') . "</label>";
    if ($pol->active == 1) {
        $print .= "<input type='checkbox' name='active' value='1' checked>";
    } else {
        $print .= "<input type='checkbox' name='active' value='1'>";
    }
    $print .= "</div>";

    $print .= "<div>";
    $print .= "<input type='submit' value='" . __('Unesi', 'super-maps') . "' class='button action' >";
    $print .= "</div>";
    $print .= "</form>";


    if (!empty($_POST)) {

        if(!empty($_POST['active'])){
            $active = $_POST['active'];
        }else{
            $active = '';
        }

        $wpdb->update(
            SUPERMAPS_DB_POLYGON,
            array(
                'name' => sanitize_text_field($_POST['name']),
                'map' => intval($_POST['map']),
                'style' => intval($_POST['style']),
                'poligon_cord' => sanitize_text_field($_POST['poligon_cord']),
                'html' => sanitize_text_field($_POST['html']),
                'date' => date("Y-m-d H:i:s"),
                'active' => $active
            ),
            array( 'id' => $_POST['id'] )
        );


        redirect_js('admin.php?page=super-maps&p=polygon');


    }

    echo $print;
}

// Polygon del

function super_maps_polygon_del()
{

    global $wpdb;

    $id = intval($_GET['pol']);

    $wpdb->delete(SUPERMAPS_DB_POLYGON, array('ID' => $id));

    redirect_js('admin.php?page=super-maps&p=polygon');
}



