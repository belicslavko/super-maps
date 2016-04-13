/**
 * Created by slavko on 11.4.16..
 */
var drawingManager;
var selectedShape;
var colors = ['#1E90FF', '#FF1493', '#32CD32', '#FF8C00', '#4B0082'];
var selectedColor;
var colorButtons = {};
var map;
var markers = [];

function clearSelection() {
    if (selectedShape) {
        selectedShape.setEditable(false);
        selectedShape = null;
    }
}

function setSelection(shape) {
    clearSelection();
    selectedShape = shape;
    shape.setEditable(true);
    selectColor(shape.get('fillColor') || shape.get('strokeColor'));
}

function deleteSelectedShape() {
    if (selectedShape) {
        selectedShape.setMap(null);
    }
}

function selectColor(color) {
    selectedColor = color;
    for (var i = 0; i < colors.length; ++i) {
        var currColor = colors[i];
        colorButtons[currColor].style.border = currColor == color ? '2px solid #789' : '2px solid #fff';
    }


    // Retrieves the current options from the drawing manager and replaces the
    // stroke or fill color as appropriate.
    var polylineOptions = drawingManager.get('polylineOptions');
    polylineOptions.strokeColor = color;
    drawingManager.set('polylineOptions', polylineOptions);

    var rectangleOptions = drawingManager.get('rectangleOptions');
    rectangleOptions.fillColor = color;
    drawingManager.set('rectangleOptions', rectangleOptions);

    var circleOptions = drawingManager.get('circleOptions');
    circleOptions.fillColor = color;
    drawingManager.set('circleOptions', circleOptions);

    var polygonOptions = drawingManager.get('polygonOptions');
    polygonOptions.fillColor = color;
    drawingManager.set('polygonOptions', polygonOptions);
}

function setSelectedShapeColor(color) {
    if (selectedShape) {
        if (selectedShape.type == google.maps.drawing.OverlayType.POLYLINE) {
            selectedShape.set('strokeColor', color);
        } else {
            selectedShape.set('fillColor', color);
        }
    }
}

function makeColorButton(color) {
    var button = document.createElement('span');
    button.className = 'color-button';
    button.style.backgroundColor = color;
    google.maps.event.addDomListener(button, 'click', function() {
        selectColor(color);
        setSelectedShapeColor(color);
    });

    return button;
}

function buildColorPalette() {
    var colorPalette = document.getElementById('color-palette');
    for (var i = 0; i < colors.length; ++i) {
        var currColor = colors[i];
        var colorButton = makeColorButton(currColor);
        colorPalette.appendChild(colorButton);
        colorButtons[currColor] = colorButton;
    }
    selectColor(colors[0]);
}

function addMarker(location) {
    var lat = location.lat();
    var lng = location.lng();


    marker = new google.maps.Marker({
        position: location,
        draggable: true,
        editable: true,
        map: map
    });

}

function initialize() {

    var selectLat = jQuery("#latitude").val();
    var selectLng = jQuery("#longitude").val();
    var selectZoom = jQuery("#zoom").val();

    if (!selectLat) {
        selectLat = 44.8000;
    }

    if (!selectLng) {
        selectLng = 20.4667;
    }

    if (!selectZoom) {
        selectZoom = 7;
    }

    mapcenter =  new google.maps.LatLng(selectLat, selectLng);

    map = new google.maps.Map(document.getElementById('supermaps'), {
        zoom: 10,
        center: mapcenter,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true,
        zoomControl: true
    });


    if (selectLat && selectLng && selectZoom) {
        addMarker(mapcenter);
    }




    var polyOptions = {
        strokeWeight: 0,
        fillOpacity: 0.45,
        editable: true
    };
    // Creates a drawing manager attached to the map that allows the user to draw
    // markers, lines, and shapes.
    drawingManager = new google.maps.drawing.DrawingManager({
        drawingControl: true,
        drawingControlOptions: {
            drawingModes: [
                google.maps.drawing.OverlayType.MARKER,
               /* google.maps.drawing.OverlayType.CIRCLE,
                google.maps.drawing.OverlayType.POLYGON,
                google.maps.drawing.OverlayType.POLYLINE,
                google.maps.drawing.OverlayType.RECTANGLE*/
            ]
        },
        drawingMode: google.maps.drawing.OverlayType.MARKER,
        markerOptions: {
            draggable: true,
            editable: true
        },
        polylineOptions: {
            editable: true
        },
        rectangleOptions: polyOptions,
        circleOptions: polyOptions,
        polygonOptions: polyOptions,
        map: map
    });



   

    google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
        if (e.type != google.maps.drawing.OverlayType.MARKER) {
            // Switch back to non-drawing mode after drawing a shape.
            drawingManager.setDrawingMode(null);

            // Add an event listener that selects the newly-drawn shape when the user
            // mouses down on it.
            var newShape = e.overlay;
            newShape.type = e.type;
            google.maps.event.addListener(newShape, 'click', function() {
                setSelection(newShape);
            });
            setSelection(newShape);
        }
    });

    google.maps.event.addListener(drawingManager, 'markercomplete', function(e) {

        console.log(e);

        var lat = e.position.lat();
        var lng = e.position.lng();
        jQuery("#latitude").val(lat);
        jQuery("#longitude").val(lng);

        marker.setMap(null);
        e.overlay.setMap(null);

    });

    google.maps.event.addListener(map, "zoom_changed", function (event) {
        var zoomLevel = map.getZoom();
        console.log(zoomLevel);
        jQuery("#zoom").val(zoomLevel);
    });

    // Clear the current selection when the drawing mode is changed, or when the
    // map is clicked.
    google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
    google.maps.event.addListener(map, 'click', clearSelection);
    google.maps.event.addDomListener(document.getElementById('delete-button'), 'click', deleteSelectedShape);

    buildColorPalette();

    jQuery("#latitude").val(lat);
    jQuery("#longitude").val(lng);
    console.log(location.lat());
    marker = new google.maps.Marker({
        position: location,
        draggable: true,
        map: map
    });


}
google.maps.event.addDomListener(window, 'load', initialize);