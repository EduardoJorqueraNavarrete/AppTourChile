<!DOCTYPE html>
<html>
  <head>
    <title>POI Click Events</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      .title {
        font-weight: bold;
      }
      #infowindow-content {
        display: none;
      }
      #map #infowindow-content {
        display: inline;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <div id="infowindow-content">
      <img id="place-icon" src="" height="16" width="16">
      <span id="place-name"  class="title"></span><br>
      Place ID <span id="place-id"></span><br>
      <span id="place-address"></span>
    </div>
    <script>
     var map, infoWindow;
function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: -30.0000000, lng: -71.0000000},
        zoom: 8
    });
    infoWindow = new google.maps.InfoWindow;
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            infoWindow.setPosition(pos);
            infoWindow.setContent('Usted esta aquí.');
            infoWindow.open(map);
            map.setCenter(pos);

            //-------------Marcador----------
            var marker = new google.maps.Marker({ position: pos, map: map });
            marker.addListener('click', function () {
                map.setZoom(18);
                map.setCenter(marker.getPosition());
            });
            var clickHandler = new ClickEventHandler(map, pos);
        }, function () {
            handleLocationError(true, infoWindow, map.getCenter());
        });
    } else {
        handleLocationError(false, infoWindow, map.getCenter());
    }
    new google.maps.Geocoder();

    var ClickEventHandler = function (map, pos) {
        this.origin = pos;
        this.map = map;
        this.directionsService = new google.maps.DirectionsService;
        this.directionsDisplay = new google.maps.DirectionsRenderer;
        this.directionsDisplay.setMap(map);
        this.placesService = new google.maps.places.PlacesService(map);
        this.infowindow = new google.maps.InfoWindow;

        this.map.addListener('click', this.handleClick.bind(this));
    };

    ClickEventHandler.prototype.handleClick = function (event) {
        console.log('Tu pinchaste en: ' + event.latLng);
        if (event.placeId) {
            console.log('Tu pinchaste en el lugar:' + event.placeId);
            event.stop();
            this.calculateAndDisplayRoute(event.placeId);
        }
    };

    ClickEventHandler.prototype.calculateAndDisplayRoute = function (placeId) {
        var me = this;
        this.directionsService.route({
            origin: me.origin,
            destination: { placeId: placeId },
            travelMode: 'DRIVING'
        }, function (response, status) {
            if (status === 'OK') {
                me.directionsDisplay.setDirections(response);
            } else {
                window.alert('Directions request failed due to ' + status);
            }
        });
    };
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(browserHasGeolocation ?
        'Error: The Geolocation service failed.' :
        'Error: Your browser doesn\'t support geolocation.');
    infoWindow.open(map);
}
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmRFPO5rFS1mnYFyxf-Xtf6bYSbJT0olM&libraries=places&callback=initMap"
        async defer></script>
  </body>
</html>