<?php



// Google maps js hook

if(!empty($_GET['p'])){

if($_GET['p']=='newPolygon' || $_GET['p']=='editPolygon') {

    add_action('admin_head', 'super_maps_polygon_js');

}

if($_GET['p']=='newLine' || $_GET['p']=='editLine') {

    add_action('admin_head', 'super_maps_line_js');

}


if($_GET['p']=='newMap' || $_GET['p']=='editMap') {

    add_action('admin_head', 'super_maps_map_js');

}

if($_GET['p']=='newMarker' || $_GET['p']=='editMarker') {

    add_action('admin_head', 'super_maps_marker_js');

}

}

// polygon js header

function super_maps_polygon_js()
{

    $print = '';

    $print .= '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';
    $print .= '<script type="text/javascript" src="' . plugins_url('super-maps/js/polygon.min.js') . '"></script>';
    $print .= "<script type='text/javascript'>
	jQuery(function(){

		  var editCords = jQuery( '#showData' ).val();
		  var strokeColor = jQuery( '#strokeColor' ).val();
		  var strokeOpacity = jQuery( '#strokeOpacity' ).val();
		  var strokeWeight = jQuery( '#strokeWeight' ).val();
		  var fillColor = jQuery( '#fillColor' ).val();
		  var fillOpacity = jQuery( '#fillOpacity' ).val();

		  if(!strokeColor){ strokeColor = '#FF0000'; }
          if(!strokeOpacity){ strokeOpacity = 0.8; }
          if(!strokeWeight){ strokeWeight = 2; }
          if(!fillColor){ fillColor = '#FF0000'; }
          if(!fillOpacity){ fillOpacity = 0.35; }



		 //create map
		 if(editCords){

		     editCordsC = ','+editCords;
             editCordsC = editCordsC.split(')');
             editCordsC = jQuery.grep(editCordsC,function(n){ return(n) });
             var ltlngC = editCordsC[0].substring(2);
             ltlngC = ltlngC.split(',');

		    var mapCenter=new google.maps.LatLng(ltlngC[0], ltlngC[1]);
            var zoom = 12;
		 }else{

		    var mapCenter=new google.maps.LatLng(53.0000, 9.0000);
		    var zoom = 4;
		 }
		 var myOptions = {
		  	zoom: zoom,
		  	center: mapCenter,
		    mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		map = new google.maps.Map(document.getElementById('map_polygon'), myOptions);

		var creator = new PolygonCreator(map);



        if(editCords){

        editCords = ','+editCords;

        editCords = editCords.split(')');

        editCords = jQuery.grep(editCords,function(n){ return(n) });

        var triangleCoords = new Array (editCords.length);

        for(i=0;i<editCords.length;i++){
            var ltlng = editCords[i].substring(2);
            ltlng = ltlng.split(',');
            triangleCoords[i] = new google.maps.LatLng(ltlng[0], ltlng[1]);
        }


        polygonObj = new google.maps.Polygon({
            paths: triangleCoords,
            editable:true,
            strokeColor: strokeColor,
            strokeOpacity: strokeOpacity,
            strokeWeight: strokeWeight,
            fillColor: fillColor,
            fillOpacity: fillOpacity
          });

          polygonObj.setMap(map);


        google.maps.event.addListener(polygonObj, 'polygoncomplete', function(polygon) {

            polygonObj.setDrawingMode(null);
            polygonArray.push(polygon);
    });


   var pathPolygon = polygonObj.getPath();
    google.maps.event.addListener(pathPolygon, 'set_at', function (event) {
          jQuery('#showData').val(pathPolygon.getArray().toString());
    });



             }

		 //reset
		 jQuery('#reset').click(function(){
		 		creator.destroy();
		 		creator=null;

		 		creator=new PolygonCreator(map);
		 		polygonObj.setMap(null);

		 		jQuery('#showData').val(null);
		 });
});

</script>";


    echo $print;

}

// polygon html

function super_maps_polygon_html($cord = false)
{

    $print = '';
    $print .= '

    <div id="map_polygon" style="width:600px; height:400px; margin-top: 20px"></div>
	<div>';

    $print .= '<input id="reset" value="Resetuj poligone" type="button" class="button action reset-poly" />';

    if ($cord) {
        $print .= '<input id="showData" type="text" name="poligon_cord" value="' . $cord . '" />';

    } else {
        $print .= '<input id="showData"  value="" type="text" name="poligon_cord"/>';

    }

    $print .= '</div>';
    return $print;

}



function super_maps_line_js()
{

    $print = '';

    $print .= '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';
    $print .= '<script type="text/javascript" src="' . plugins_url('super-maps/js/line.min.js') . '"></script>';
    $print .= "<script type='text/javascript'>
	jQuery(function(){

		  var editCords = jQuery( '#showData' ).val();
		  var strokeColor = jQuery( '#strokeColor' ).val();
		  var strokeOpacity = jQuery( '#strokeOpacity' ).val();
		  var strokeWeight = jQuery( '#strokeWeight' ).val();
		  var fillColor = jQuery( '#fillColor' ).val();
		  var fillOpacity = jQuery( '#fillOpacity' ).val();

		  if(!strokeColor){ strokeColor = '#FF0000'; }
          if(!strokeOpacity){ strokeOpacity = 0.8; }
          if(!strokeWeight){ strokeWeight = 2; }
          if(!fillColor){ fillColor = '#FF0000'; }
          if(!fillOpacity){ fillOpacity = 0.35; }



		 //create map
		 if(editCords){

		     editCordsC = ','+editCords;
             editCordsC = editCordsC.split(')');
             editCordsC = jQuery.grep(editCordsC,function(n){ return(n) });
             var ltlngC = editCordsC[0].substring(2);
             ltlngC = ltlngC.split(',');

		    var mapCenter=new google.maps.LatLng(ltlngC[0], ltlngC[1]);
            var zoom = 12;
		 }else{

		    var mapCenter=new google.maps.LatLng(53.0000, 9.0000);
		    var zoom = 4;
		 }
		 var myOptions = {
		  	zoom: zoom,
		  	center: mapCenter,
		    mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		map = new google.maps.Map(document.getElementById('map_polygon'), myOptions);

		var creator = new LineCreator(map);



        if(editCords){

        editCords = ','+editCords;

        editCords = editCords.split(')');

        editCords = jQuery.grep(editCords,function(n){ return(n) });

        var triangleCoords = new Array (editCords.length);

        for(i=0;i<editCords.length;i++){
            var ltlng = editCords[i].substring(2);
            ltlng = ltlng.split(',');
            triangleCoords[i] = new google.maps.LatLng(ltlng[0], ltlng[1]);
        }


        polylineObj = new google.maps.Polyline({
            path: triangleCoords,
            geodesic: true,
            editable:true,
            strokeColor: strokeColor,
            strokeOpacity: strokeOpacity,
            strokeWeight: strokeWeight

          });

          polylineObj.setMap(map);


        google.maps.event.addListener(polylineObj, 'polylinecomplete', function(polygon) {

            polylineObj.setDrawingMode(null);
            polygonArray.push(polygon);
    });


   var pathPolyline = polylineObj.getPath();
    google.maps.event.addListener(pathPolyline, 'set_at', function (event) {
          jQuery('#showData').val(pathPolyline.getArray().toString());
    });



             }

		 //reset
		 jQuery('#reset').click(function(){
		 		creator.destroy();
		 		creator=null;

		 		creator=new LineCreator(map);
		 		polylineObj.setMap(null);

		 		jQuery('#showData').val(null);
		 });
});

</script>";


    echo $print;

}

// polygon html

function super_maps_line_html($cord = false)
{

    $print = '';
    $print .= '

    <div id="map_polygon" style="width:600px; height:400px; margin-top: 20px"></div>
	<div>';

    $print .= '<input id="reset" value="Resetuj linije" type="button" class="button action reset-poly" />';

    if ($cord) {
        $print .= '<input id="showData" type="text" name="poligon_cord" value="' . $cord . '" />';

    } else {
        $print .= '<input id="showData"  value="" type="text" name="poligon_cord"/>';

    }

    $print .= '</div>';
    return $print;

}



// Category js header hook

function super_maps_map_js(){

    $print = "";

    $print .= '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';
    $print .= "<script>
    var map;
    var markers = [];
    function initialize() {

        var selectLat = jQuery('#latitude').val();
        var selectLng = jQuery('#longitude').val();
        var selectZoom = jQuery('#zoom').val();

        if(!selectLat){
        selectLat = 44.8000;

        }

        if(!selectLng){
        selectLng = 20.4667;

        }

        if(!selectZoom){
        selectZoom = 7;

        }


        console.log(selectZoom);

        var haightAshbury = new google.maps.LatLng(selectLat, selectLng);
        var mapOptions = {
            zoom: Number(selectZoom),
            center: haightAshbury,
            mapTypeId: google.maps.MapTypeId.TERRAIN
        };
        map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

        google.maps.event.addListener(map, 'click', function(event) {
            if(markers.length==0){
                addMarker(event.latLng);
            }
        });

        if(selectLat && selectLng && selectZoom){
        addMarker(haightAshbury);

        }

        function addMarker(location){
            var lat = location.lat();
            var lng = location.lng();

            jQuery('#latitude').val(lat);
            jQuery('#longitude').val(lng);
            console.log(location.lat());
            var marker = new google.maps.Marker({
                position: location,
                draggable:true,
                map: map
            });
            markers.push(marker);
            google.maps.event.addListener(marker, 'dragend', function() {
                var lat = marker.position.lat();
                var lng = marker.position.lng();
                jQuery('#latitude').val(lat);
                jQuery('#longitude').val(lng);
            });
            google.maps.event.addListener(marker, 'dblclick', function(event) { //brise marker i input polja
                clearMarkers();
                markers = [];
                jQuery('#latitude').val('');
                jQuery('#longitude').val('');
            });

            google.maps.event.addListener(map, 'zoom_changed', function(event) {
                var zoomLevel = map.getZoom();
                console.log(zoomLevel);
                jQuery('#zoom').val(zoomLevel);
            });


        }

    }
    function setAllMap(map) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }
    }
    function clearMarkers() {
        setAllMap(null);
    }
    function showMarkers() {
        setAllMap(map);
    }
    function deleteMarkers() {
        clearMarkers();
        markers = [];
        jQuery('#latitude').val('');
        jQuery('#longitude').val('');
    }
    google.maps.event.addDomListener(window, 'load', initialize);
</script>";


    echo $print;

}


// Category html map

function super_maps_map_html($longitude=false, $latitude=false){

    $print = "";

    $print .= '<div class="form-group">
    <label class="col-md-2 control-label" for="inputText1">Longitude </label>
    <div class="col-md-5">
        <input class="form-control" id="longitude" name="longitude" value="'.$longitude.'" type="text" >
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label" for="inputText1">Latitude </label>
    <div class="col-md-5">
        <input class="form-control" id="latitude" name="latitude" value="'.$latitude.'" type="text" >
    </div>
</div>
<div id="map-canvas" style="width: 500px; height: 400px;"></div>';

    return $print;


}

// Marker js header hook

function super_maps_marker_js(){

    $print = "";

    $print .= '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';
    $print .= "<script>
    var map;
    var markers = [];
    function initialize() {

        var selectLat = jQuery('#latitude').val();
        var selectLng = jQuery('#longitude').val();
        var selectZoom = jQuery('#zoom').val();

        var marker_ico = jQuery( '#upload_image' ).val();

        var marker_html = jQuery( '#html' ).val();

        if(!selectLat){
        selectLat = 44.8000;

        }

        if(!selectLng){
        selectLng = 20.4667;

        }

        if(!selectZoom){
        selectZoom = 7;

        }


        console.log(selectZoom);

        var haightAshbury = new google.maps.LatLng(selectLat, selectLng);
        var mapOptions = {
            zoom: Number(selectZoom),
            center: haightAshbury,
            mapTypeId: google.maps.MapTypeId.TERRAIN
        };
        map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

        google.maps.event.addListener(map, 'click', function(event) {
            if(markers.length==0){
                addMarker(event.latLng);
            }
        });


        var infowindow = new google.maps.InfoWindow({
            content: marker_html
        });

        google.maps.event.addListener(haightAshbury, 'mouseover', function() {
        infowindow.open(map , haightAshbury);
        });




        if(selectLat && selectLng && selectZoom){
        addMarker(haightAshbury);

        }

        function addMarker(location){
            var lat = location.lat();
            var lng = location.lng();

            jQuery('#latitude').val(lat);
            jQuery('#longitude').val(lng);
            console.log(location.lat());
            var marker = new google.maps.Marker({
                position: location,
                draggable:true,
                map: map,
                icon: marker_ico,
                title: marker_html

            });
            markers.push(marker);
            google.maps.event.addListener(marker, 'dragend', function() {
                var lat = marker.position.lat();
                var lng = marker.position.lng();
                jQuery('#latitude').val(lat);
                jQuery('#longitude').val(lng);
            });
            google.maps.event.addListener(marker, 'dblclick', function(event) { //brise marker i input polja
                clearMarkers();
                markers = [];
                jQuery('#latitude').val('');
                jQuery('#longitude').val('');
            });

            google.maps.event.addListener(map, 'zoom_changed', function(event) {
                var zoomLevel = map.getZoom();
                console.log(zoomLevel);
                jQuery('#zoom').val(zoomLevel);
            });


        }

    }
    function setAllMap(map) {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(map);
        }
    }
    function clearMarkers() {
        setAllMap(null);
    }
    function showMarkers() {
        setAllMap(map);
    }
    function deleteMarkers() {
        clearMarkers();
        markers = [];
        jQuery('#latitude').val('');
        jQuery('#longitude').val('');
    }
    google.maps.event.addDomListener(window, 'load', initialize);
</script>";


    echo $print;

}

// Marker html

function super_maps_marker_html($longitude=false, $latitude=false){

    $print = "";

    $print .= '<div class="form-group">
    <label class="col-md-2 control-label" for="inputText1">Longitude </label>
    <div class="col-md-5">
        <input class="form-control" id="longitude" name="longitude" value="'.$longitude.'" type="text" >
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label" for="inputText1">Latitude </label>
    <div class="col-md-5">
        <input class="form-control" id="latitude" name="latitude" value="'.$latitude.'" type="text" >
    </div>
</div>
<div id="map-canvas" style="width: 500px; height: 400px;"></div>';

    return $print;


}

