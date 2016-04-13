<?php


// [supermaps map="1"]
function supermaps_public($atts)
{

    $css =  cs_get_option( 'custom_css' );
    $after_js =  cs_get_option( 'custom_after_js' );
    $before_js =  cs_get_option( 'custom_before_js' );

    $id = intval($atts['map']);

    $map = get_post($id);

    $map_data = get_post_meta($id, '_custom_supermaps_options', true);

    $args_marker = array(
        'post_type' => 'supermaps_marker',
        'meta_query' => array(
            array(
                'key' => '_custom_supermaps_marker_options',
                'value' => $id,
                'compare' => 'LIKE'

            )
        )
    );
    $marker = get_posts($args_marker);


    $args_line = array(
        'post_type' => 'supermaps_line',
        'meta_query' => array(
            array(
                'key' => '_custom_supermaps_line_options',
                'value' => $id,
                'compare' => 'LIKE'

            )
        )
    );
    $line = get_posts($args_line);


    $args_polygon = array(
        'post_type' => 'supermaps_polygon',
        'meta_query' => array(
            array(
                'key' => '_custom_supermaps_polygon_options',
                'value' => $id,
                'compare' => 'LIKE'

            )
        )
    );
    $polygon = get_posts($args_polygon);

    $args_overlay = array(
        'post_type' => 'supermaps_overlay',
        'meta_query' => array(
            array(
                'key' => '_custom_supermaps_overlay_options',
                'value' => $id,
                'compare' => 'LIKE'

            )
        )
    );
    $overlay = get_posts($args_overlay);


    if (empty($map)) {

        echo "ERROR: MAP not found!";

    }


    if ($map_data['scroll'] == 1) {
        $scroll = 'true';
    } else {
        $scroll = 'false';
    }


    if ($map_data['draggable'] == 1) {
        $draggable = 'true';
    } else {
        $draggable = 'false';
    }

    $print = '';


    if(!empty($css)){

        $print .= "<style>".$css."</style>";

    }

    if(!empty($before_js)){

        $print .= "<script>".$before_js."</script>";

    }


    $print .= "

    <script>

        var map" . $id . ";
        var markers = [];
        var overlays = [];

        function initialize" . $id . "() {

        var mapOptions = {
           zoom: " . $map_data['zoom'] . ",
           scrollwheel: " . $scroll . ",
           draggable: ".$draggable.",
           center: new google.maps.LatLng(" . $map_data['latitude'] . ", " . $map_data['longitude'] . "),
           mapTypeId: google.maps.MapTypeId.TERRAIN
    ";


    $print .= "
    
    };

        map" . $id . " = new google.maps.Map(document.getElementById('map_polygon" . $id . "'), mapOptions);";


    if (!empty($map_data['theme_array'])) {

        $print .= "
        var styles = " . stripslashes($map_data['theme_array']);
        $print .= "
        map" . $id . ".setOptions({styles: styles});";

    }


    foreach ($polygon as $p) {
        $polygon_data = get_post_meta($p->ID, '_custom_supermaps_polygon_options', true);


        $print .= "var strokeColor = '" . $polygon_data['stroke_color'] . "';";
        $print .= "var strokeOpacity = '" . $polygon_data['stroke_opacity'] . "';";
        $print .= "var strokeWeight = '" . $polygon_data['stroke_weight'] . "';";
        $print .= "var fillColor = '" . $polygon_data['fill_color'] . "';";
        $print .= "var fillOpacity = '" . $polygon_data['fill_opacity'] . "';";

        $editCords = ',' . $polygon_data['cords'];
        $print .= "
        editCords = '" . $editCords . "';

        editCords = editCords.split(')');

        editCords = jQuery.grep(editCords,function(n){ return(n) });

        var triangleCoords" . $p->ID . " = new Array (editCords.length);

        for(i=0;i<editCords.length;i++){
            var ltlng = editCords[i].substring(2);
            ltlng = ltlng.split(',');
            triangleCoords" . $p->ID . "[i] = new google.maps.LatLng(ltlng[0], ltlng[1]);
        }

        polygonObj" . $p->ID . " = new google.maps.Polygon({
            paths: triangleCoords" . $p->ID . ",
            strokeColor: strokeColor,
            strokeOpacity: strokeOpacity,
            strokeWeight: strokeWeight,
            fillColor: fillColor,
            fillOpacity: fillOpacity
          });

          polygonObj" . $p->ID . ".setMap(map" . $id . ");";

    }


    foreach ($line as $l) {

        $line_data = get_post_meta($l->ID, '_custom_supermaps_line_options', true);

        $print .= "var strokeColorLine = '" . $line_data['stroke_color'] . "';";
        $print .= "var strokeOpacityLine = '" . $line_data['stroke_opacity'] . "';";
        $print .= "var strokeWeightLine = '" . $line_data['stroke_weight'] . "';";

        $editCords = ',' . $line_data['cords'];
        $print .= "
        editCords = '" . $editCords . "';

        editCords = editCords.split(')');

        editCords = jQuery.grep(editCords,function(n){ return(n) });

        var triangleCoordsLine" . $l->ID . " = new Array (editCords.length);

        for(i=0;i<editCords.length;i++){
            var ltlng = editCords[i].substring(2);
            ltlng = ltlng.split(',');
            triangleCoordsLine" . $l->ID . "[i] = new google.maps.LatLng(ltlng[0], ltlng[1]);
        }

        console.log(triangleCoordsLine" . $l->ID . ");

        lineObj" . $l->ID . " = new google.maps.Polyline({
            path: triangleCoordsLine" . $l->ID . ",
            strokeColor: strokeColorLine,
            strokeOpacity: strokeOpacityLine,
            strokeWeight: strokeWeightLine

          });

          lineObj" . $l->ID . ".setMap(map" . $id . ");";

    }


    foreach ($marker as $m) {


        $marker_data = get_post_meta($m->ID, '_custom_supermaps_marker_options', true);


        $print .= "
    
        var marker" . $m->ID . " = new google.maps.LatLng(" . $marker_data['latitude'] . ", " . $marker_data['longitude'] . ");
        var marker = new google.maps.Marker({
            position: marker" . $m->ID . ",
            map: map" . $map->ID . ",
            animation: google.maps.Animation.DROP,
            title:'" . $marker_data['html'] . "',";

        $img = wp_get_attachment_image_src($marker_data['marker_img'], 'full');

        if (!empty($img[0]))
            $print .= "icon: '" . $img[0] . "',";

        $print .= "});

        markers.push(marker);";

        if(!empty($marker_data['html'])){
        $print .= " var infowindow" . $id . " = new google.maps.InfoWindow({
            content: '" .trim(str_replace( array("\r\n","\r","\n",'  '), ' ' , $marker_data['html']))  . "'
        });
        infowindow" . $id . ".open(map" . $id . ", marker);

        ";
        }

    }


    foreach ($overlay as $o) {


        $overlay_data = get_post_meta($o->ID, '_custom_supermaps_overlay_options', true);


        $print .= "var centerSfo" . $o->ID . " = new google.maps.LatLng(" . $overlay_data['latitude'] . ", " . $overlay_data['longitude'] . ");

            circle = new google.maps.Circle({radius: " . $overlay_data['radius'] . ", center: centerSfo" . $o->ID . "});
            imageBounds" . $o->ID . " = circle.getBounds();


            historicalOverlay" . $o->ID . " = new google.maps.GroundOverlay('" . wp_get_attachment_image_src($overlay_data['overlay_img'], 'full')[0] . "', imageBounds" . $o->ID . ");
            historicalOverlay" . $o->ID . ".setMap(map" . $id . ");
  
            overlays.push(historicalOverlay" . $o->ID . ");
  
        ";

    }

    $print .= "console.log(overlays);";

    $print .= "

    }



    function endisObject(name, id){

        var checked = jQuery(id).prop('checked');

        if(!checked){

            name.setMap(null);

        }else{

            name.setMap(map" . $id . ");
        }
        
    }

    function endisMarker(markerId, id) {
    
    console.log(markers);

     var checked = jQuery(id).prop('checked');

        if(!checked){

            for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
            }            

        }else{

            for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(map" . $id . ");
            }
             
        }

    }
    
    
    function endisOverlay(markerId, id) {

    var checked = jQuery(id).prop('checked');

        if(!checked){

            for (var i = 0; i < overlays.length; i++) {
            overlays[i].setMap(null);
            }

        }else{

            for (var i = 0; i < overlays.length; i++) {
            overlays[i].setMap(map" . $id . ");
            }      
             
        }

    }
    
    function enableTouch(b) {
        console.log(b);
        if (jQuery('#enableMap".$id."').is(':checked')){
            drag = 1;
        }else{
            drag = 0;
        }
        map".$id.".setOptions({
            draggable: drag
        });

    }


    ";

    $print .= "google.maps.event.addDomListener(window, 'load', initialize" . $id . ");";

    $print .= "</script>";

    $print .= '<div id="map_polygon' . $id . '" style="width:' . $map_data['width'] . 'px; height:' . $map_data['height'] . 'px"></div>';


    $print .= "<div id='checkMaps'>";




    if ($map_data['map_enable_layer'] == 1) {
        $print .= "";



            $print .= '<p><input class="super-maps-check" id="enableMap' . $id . '" checked type="checkbox" onclick="enableTouch()" /> Enable map</p>';


    }

    if (!empty($polygon) && ($map_data['polygon_layer'] == 1)) {
        $print .= "<p>Poligoni</p>";

        foreach ($polygon as $p) {

            $print .= '<p><input class="super-maps-check" id="namepolygonObj' . $p->ID . '" checked type="checkbox" onclick="endisObject(polygonObj' . $p->ID . ', namepolygonObj' . $p->ID . ')" /> ' . $p->title . '</p>';

        }
    }

    if (!empty($line) && ($map_data['line_layer'] == 1)) {
        $print .= "<p>Linije</p>";

        foreach ($line as $l) {

            $print .= '<p><input class="super-maps-check" id="namelineObj' . $l->ID . '" checked type="checkbox" onclick="endisObject(lineObj' . $l->ID . ', namelineObj' . $l->ID . ')" /> ' . $l->title . '</p>';

        }
    }

    if (!empty($marker) && ($map_data['marker_layer'] == 1)) {

        $print .= "<p>Markeri</p>";

        foreach ($marker as $m) {

            $print .= '<p><input class="super-maps-check" id="namemarkers' . $m->ID . '" checked type="checkbox" onclick="endisMarker(' . $m->ID . ', namemarkers' . $m->ID . ')" /> ' . $m->title . '</p>';

        }
    }

    if (!empty($overlay) && ($map_data['overlay_layer'] == 1)) {

        $print .= "<p>Overlay</p>";

        foreach ($overlay as $m) {

            $print .= '<p><input class="super-maps-check" id="nameoverlay' . $m->ID . '" checked type="checkbox" onclick="endisOverlay(' . $m->ID . ', nameoverlay' . $m->ID . ')" /> ' . $m->title . '</p>';

        }
    }


    $print .= "</div>";

    if(!empty($after_js)){

        $print .= "<script>".$after_js."</script>";

    }

    return $print;

}

add_shortcode('supermaps', 'supermaps_public');