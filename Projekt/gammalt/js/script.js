var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var map;

function initialize() {
  directionsDisplay = new google.maps.DirectionsRenderer();
  var lund = new google.maps.LatLng(55.6924199, 13.1867336);
  var mapOptions = {
    zoom:9,
    center: lund
  }
  map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
  directionsDisplay.setMap(map);
  directionsDisplay.setPanel(document.getElementById("infohere"));
}

function calcRoute() {
  var start = "Blekingevägen 3A, Lund, Sverige"; //new google.maps.LatLng(55.6924199, 13.1867336);
  var end = "Pärlemorvägen 1, Lund, Sverige"; //, Lund, Sverige"; //new google.maps.LatLng(55.6524199, 13.3267336);
  var request = {
    origin:end,
    destination:start,
    travelMode: google.maps.TravelMode.BICYCLING
  };
  directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
      directionsDisplay.setDirections(response);
    }
    else {
        console.log(status);
    }
  });
}

window.onload = function() {
    initialize();
    calcRoute();
};