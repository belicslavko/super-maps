/**
 * Created by slavko on 11.4.16..
 */
var drawingManager;
var selectedShape;
var colors = ['#1E90FF', '#FF1493', '#32CD32', '#FF8C00', '#4B0082'];
var selectedColor;
var colorButtons = {};
var map;

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



function initialize() {


    var editCords = jQuery('#showData').val();
    var strokeColor = jQuery('#strokeColor').val();
    var strokeOpacity = jQuery('#strokeOpacity').val();
    var strokeWeight = jQuery('#strokeWeight').val();
    var fillColor = jQuery('#fillColor').val();
    var fillOpacity = jQuery('#fillOpacity').val();

    if (!strokeColor) {
        strokeColor = '#000000';
    }
    if (!strokeOpacity) {
        strokeOpacity = 0.8;
    }
    if (!strokeWeight) {
        strokeWeight = 2;
    }
    if (!fillColor) {
        fillColor = '#000000';
    }
    if (!fillOpacity) {
        fillOpacity = 0.35;
    }

    var map = new google.maps.Map(document.getElementById('supermaps'), {
        zoom: 10,
        center: new google.maps.LatLng(22.344, 114.048),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true,
        zoomControl: true
    });

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
                /*
                google.maps.drawing.OverlayType.MARKER,
                google.maps.drawing.OverlayType.CIRCLE,
                google.maps.drawing.OverlayType.POLYGON,*/
                google.maps.drawing.OverlayType.POLYLINE,/*
                google.maps.drawing.OverlayType.RECTANGLE*/
            ]
        },
        drawingMode: google.maps.drawing.OverlayType.POLYLINE,
        markerOptions: {
            draggable: true,
            editable: true
        },
        polylineOptions: {
            editable: true,

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
            setSelection(newShape);
        }
    });

    google.maps.event.addListener(drawingManager, 'polylinecomplete', function(e) {


        jQuery('#showData').val(e.getPath().getArray().toString());


    });


    if (editCords) {

        editCords = ',' + editCords;

        editCords = editCords.split(')');

        editCords = jQuery.grep(editCords, function (n) {
            return (n)
        });

        var triangleCoords = new Array(editCords.length);

        for (i = 0; i < editCords.length; i++) {
            var ltlng = editCords[i].substring(2);
            ltlng = ltlng.split(',');
            triangleCoords[i] = new google.maps.LatLng(ltlng[0], ltlng[1]);
        }


        polylineObj = new google.maps.Polyline({
            path: triangleCoords,
            geodesic: true,
            editable: true,
            strokeColor: strokeColor,
            strokeOpacity: strokeOpacity,
            strokeWeight: strokeWeight

        });

        polylineObj.setMap(map);

    }




    // Clear the current selection when the drawing mode is changed, or when the
    // map is clicked.
    google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
    google.maps.event.addListener(map, 'click', clearSelection);
    google.maps.event.addDomListener(document.getElementById('reset-map'), 'click', function(e) {
        deleteSelectedShape();

        polylineObj.setMap(null);

        jQuery('#showData').val(null);
    });




}





google.maps.event.addDomListener(window, 'load', initialize);