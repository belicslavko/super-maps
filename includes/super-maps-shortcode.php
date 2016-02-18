<?php


add_shortcode( 'super_maps', 'super_maps_public' );

// [super_maps map="1"]
function super_maps_public( $atts ) {

    global $wpdb;

    $id = intval($atts['map']);


    $map = $wpdb->get_row("SELECT * FROM ".SUPERMAPS_DB_MAP ." WHERE id = ".$id);
    $map_style = $wpdb->get_row("SELECT * FROM ".SUPERMAPS_DB_STYLE_MAP ." WHERE id = ".$map->style);

    if(empty($map)){

        echo "ERROR: MAP not found!";

    }

    $pol = $wpdb->get_results("SELECT * FROM ".SUPERMAPS_DB_POLYGON ." WHERE map = ".$id);

    $mark = $wpdb->get_results("SELECT * FROM ".SUPERMAPS_DB_MARKER ." WHERE map = ".$id);

    $line = $wpdb->get_results("SELECT * FROM ".SUPERMAPS_DB_LINE ." WHERE map = ".$id);


    if($map->scroll == 1){
       $scroll = 'true';
    }else{
        $scroll = 'false';
    }

    $print = '';


    $print .= "<script>

        var map".$map->id.";
        var markers = [];

        function initialize".$map->id."() {

        var mapOptions = {
           zoom: " . $map->zoom . ",
           scrollwheel: " . $scroll . ",
           center: new google.maps.LatLng(" . $map->latitude . ", " . $map->longitude . "),
           mapTypeId: google.maps.MapTypeId.TERRAIN
           ";


    $print .= "    };

        map".$map->id." = new google.maps.Map(document.getElementById('map_polygon".$map->id."'),
              mapOptions);";


    if(!empty($map_style->theme_array)){

        $print .= "
        var styles = ".stripslashes($map_style->theme_array);
        $print .= "
        map".$map->id.".setOptions({styles: styles});";

    }


    foreach($pol as $p){

        $style_pol = $wpdb->get_row("SELECT * FROM ". SUPERMAPS_DB_STYLE_POLYGON ." where id =".$p->style);

        $print .= "var strokeColor = '".$style_pol->stroke_color."';";
        $print .= "var strokeOpacity = '".$style_pol->stroke_opacity."';";
        $print .= "var strokeWeight = '".$style_pol->stroke_weight."';";
        $print .= "var fillColor = '".$style_pol->fill_color."';";
        $print .= "var fillOpacity = '".$style_pol->fill_opacity."';";

        $editCords = ','.$p->poligon_cord;
        $print .= "
        editCords = '".$editCords."';

        editCords = editCords.split(')');

        editCords = jQuery.grep(editCords,function(n){ return(n) });

        var triangleCoords".$p->id." = new Array (editCords.length);

        for(i=0;i<editCords.length;i++){
            var ltlng = editCords[i].substring(2);
            ltlng = ltlng.split(',');
            triangleCoords".$p->id."[i] = new google.maps.LatLng(ltlng[0], ltlng[1]);
        }

        polygonObj".$p->id." = new google.maps.Polygon({
            paths: triangleCoords".$p->id.",
            strokeColor: strokeColor,
            strokeOpacity: strokeOpacity,
            strokeWeight: strokeWeight,
            fillColor: fillColor,
            fillOpacity: fillOpacity
          });

          polygonObj".$p->id.".setMap(map".$map->id.");";

    }


    foreach($line as $l){

        $style_line = $wpdb->get_row("SELECT * FROM ". SUPERMAPS_DB_STYLE_LINE ." where id =".$l->style);

        $print .= "var strokeColorLine = '".$style_line->stroke_color."';";
        $print .= "var strokeOpacityLine = '".$style_line->stroke_opacity."';";
        $print .= "var strokeWeightLine = '".$style_line->stroke_weight."';";


        $editCords = ','.$l->poligon_cord;
        $print .= "
        editCords = '".$editCords."';

        editCords = editCords.split(')');

        editCords = jQuery.grep(editCords,function(n){ return(n) });

        var triangleCoordsLine".$l->id." = new Array (editCords.length);

        for(i=0;i<editCords.length;i++){
            var ltlng = editCords[i].substring(2);
            ltlng = ltlng.split(',');
            triangleCoordsLine".$l->id."[i] = new google.maps.LatLng(ltlng[0], ltlng[1]);
        }

        console.log(triangleCoordsLine".$l->id.");

        lineObj".$l->id." = new google.maps.Polyline({
            path: triangleCoordsLine".$l->id.",
            strokeColor: strokeColorLine,
            strokeOpacity: strokeOpacityLine,
            strokeWeight: strokeWeightLine

          });

          lineObj".$l->id.".setMap(map".$map->id.");";

    }




    foreach($mark as $m){

    $style_mark = $wpdb->get_row("SELECT * FROM ". SUPERMAPS_DB_STYLE_MARKER ." where id =".$m->style);



    $print .= "
    var marker".$m->id." = new google.maps.LatLng(".$m->latitude.", ".$m->longitude.");
    var marker = new google.maps.Marker({
    position: marker".$m->id.",
    map: map".$map->id.",
    animation: google.maps.Animation.DROP,
    title:'".$m->html."',";

    if(!empty($style_mark->file))
    $print .= "icon: '".$style_mark->file."',";

    $print .= "});

    markers.push(marker);

    ";




    }

    $print .="console.log(markers);";

    $print .="

    }



    function endisObject(name, id){



        var checked = jQuery(id).prop('checked');

        if(!checked){

            name.setMap(null);

        }else{

            name.setMap(map".$map->id.");
        }


    }

    function endisMarker(markerId, id) {

     var checked = jQuery(id).prop('checked');


        if(!checked){

            markers[markerId-1].setMap(null);

        }else{

             markers[markerId-1].setMap(map".$map->id.");
        }



    }

    ";

    if(empty($map->load_variable)) {
        $print .= "google.maps.event.addDomListener(window, 'load', initialize".$map->id.");";
    } else {
        $print .= "$( '".$map->load_variable."' ).click(function() {


        $( document ).ajaxComplete(function() {

        initialize".$map->id."();

        var center".$map->id." = map".$map->id.".getCenter();
        google.maps.event.trigger(map".$map->id.", 'resize');
        map".$map->id.".setCenter(center".$map->id.");


        });

        });";
    }

    $print .= "</script>";

    $print .= '<div id="map_polygon'.$map->id.'" style="width:'.$map_style->width.'; height:'.$map_style->height.'"></div>';


    $print .= "<div id='checkMaps'>";

    if(!empty($pol) && ($map->layerPolygon == 1)) {
        $print .= "<p>Poligoni</p>";

        foreach ($pol as $p) {

            $print .= '<p><input class="super-maps-check" id="namepolygonObj' . $p->id . '" checked type="checkbox" onclick="endisObject(polygonObj' . $p->id . ', namepolygonObj' . $p->id . ')" /> ' . $p->name . '</p>';

        }
    }

    if(!empty($line) && ($map->layerLine == 1)) {
        $print .= "<p>Linije</p>";

        foreach ($line as $l) {

            $print .= '<p><input class="super-maps-check" id="namelineObj' . $l->id . '" checked type="checkbox" onclick="endisObject(lineObj' . $l->id . ', namelineObj' . $l->id . ')" /> ' . $l->name . '</p>';

        }
    }

    if(!empty($mark) && ($map->layerMarker == 1)) {

        $print .= "<p>Markeri</p>";

        foreach ($mark as $m) {

            $print .= '<p><input class="super-maps-check" id="namemarkers' . $m->id . '" checked type="checkbox" onclick="endisMarker(' . $m->id . ', namemarkers' . $m->id . ')" /> ' . $m->name . '</p>';

        }
    }

    $print .= "</div>";



    return $print;


}