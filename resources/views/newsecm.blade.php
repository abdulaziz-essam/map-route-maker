<!DOCTYPE html>
<html>
<head>
  <title>Edit Data</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
  <h1>Edit Data:</h1>
  <div id="map" style="height: 400px;"></div>
  <div id="edit-form">
    <label for="search-input">Search:</label>
    <input type="text" id="search-input">
    <div id="location-inputs"></div>
    <button id="add-marker-btn">Add Marker</button>
    <input type="hidden" id="data-id">
  </div>

  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBKLSpPTjBW85app41q294VhRivfau8inQ&libraries=places,directions&callback=initMap" type="text/javascript"></script>
  <script>
let editdataStr = localStorage.getItem("editData");
let editdata = JSON.parse(editdataStr);

let map;
let markers = [];
let directionsService;
let directionsRenderer;
let autocomplete;

function initMap() {
  console.log("Initializing map...");

  directionsService = new google.maps.DirectionsService();
  directionsRenderer = new google.maps.DirectionsRenderer();

  const myLatLng = { lat: -25.363, lng: 131.044 };
  map = new google.maps.Map(document.getElementById("map"), {
    zoom: 4,
    center: myLatLng,
  });

  directionsRenderer.setMap(map);

  var addMarkerBtn = document.getElementById("add-marker-btn");
  addMarkerBtn.addEventListener("click", function() {
    console.log("Adding marker...");

    var locationInputs = document.querySelectorAll(".location-fields");
    locationInputs.forEach(function(input) {
      var latitude = parseFloat(input.querySelector(".latitude-input").value);
      var longitude = parseFloat(input.querySelector(".longitude-input").value);
      var name = input.querySelector(".name-input").value;

      console.log("Latitude: " + latitude);
      console.log("Longitude: " + longitude);
      console.log("Name: " + name);

      var marker = new google.maps.Marker({
        position: { lat: latitude, lng: longitude },
        map: map,
        title: name
      });

      markers.push(marker);

      map.setCenter(marker.getPosition());
    });
  });

  // Add a button to calculate the route
  var calcRouteBtn = document.createElement("button");
  calcRouteBtn.textContent = "Calculate Route";
  document.getElementById("edit-form").appendChild(calcRouteBtn);
  calcRouteBtn.addEventListener("click", function() {
    console.log("Calculating route...");

    // Check that there are at least two markers on the map
    if (markers.length < 2) {
      console.log("Error: Need at least two markers to calculate a route.");
      return;
    }

    // Get the waypoints for the route
    var waypoints = markers.slice(1, -1).map(function(marker) {
      return {
        location: marker.getPosition(),
        stopover: true
      };
    });

    // Set the origin and destination for the route
    var origin = markers[0];
    var destination = markers[markers.length - 1];

    // Check that the origin and destination are valid LatLng objects
    if (!origin || !destination || !origin.getPosition() || !destination.getPosition()) {
      console.log("Error: Invalid origin or destination.");
      return;
    }

    // Set the travel mode for the route
    var travelMode = google.maps.TravelMode.DRIVING;

    // Call the Directions API to calculate the route
    directionsService.route({
      origin: origin.getPosition(),
      destination: destination.getPosition(),
      waypoints: waypoints,
      travelMode: travelMode
    }, function(result, status) {
      if (status === google.maps.DirectionsStatus.OK) {
        directionsRenderer.setDirections(result);
      } else {
        console.log("Error calculating route: " + status);
      }
    });
  });

  // Get the edit data from the API
  let editdataNum = parseInt(editdata);
  let showRoute="/show/"+editdataNum;

  axios.get(showRoute)
    .then(function (response) {
      console.log(response.data);
      console.log("show route is " + showRoute);
      console.log(editdataStr);

      var locations = response.data.pin.locations;
      var locationInputs = document.getElementById("location-inputs");
      locationInputs.innerHTML = '';

      locations.forEach(function(locationData, index) {
        var latitude = locationData.latitude      
          var longitude = locationData.longitude;
        var name = locationData.name;

        var locationFieldsHTML = `
          <div class="location-fields" data-index="${index}">
            <label for="data-latitude-${index}">Latitude:</label>
            <input type="text" class="latitude-input" value="${latitude}">
            <label for="data-name-${index}">Name:</label>
            <input type="text" class="name-input" value="${name}">
            <label for="data-longitude-${index}">Longitude:</label>
            <input type="text" class="longitude-input" value="${longitude}">
          </div>
        `;

        locationInputs.insertAdjacentHTML("beforeend", locationFieldsHTML);

        var marker = new google.maps.Marker({
          position: { lat: latitude, lng: longitude },
          map: map,
title: name
        });

        markers.push(marker);
      });

      // Check if directionsRenderer is defined before using it
      if (typeof directionsRenderer !== "undefined") {
        directionsRenderer.setMap(map);
      }

      // Calculate the route if there are at least two markers on the map
      if (markers.length >= 2) {
        var waypoints = markers.slice(1, -1).map(function(marker) {
          return {
            location: marker.getPosition(),
            stopover: true
          };
        });

        var origin = markers[0];
        var destination = markers[markers.length - 1];

        if (origin && destination && origin.getPosition() && destination.getPosition()) {
          var travelMode = google.maps.TravelMode.DRIVING;

          directionsService.route({
            origin: origin.getPosition(),
            destination: destination.getPosition(),
            waypoints: waypoints,
            travelMode: travelMode
          }, function(result, status) {
            if (status === google.maps.DirectionsStatus.OK) {
              directionsRenderer.setDirections(result);
            } else {
              console.log("Error calculating route: " + status);
            }
          });
        } else {
          console.log("Error: Invalid origin or destination.");
        }
      }
    })
    .catch(function (error) {
     console.log(error);
    });
}
  </script>
</body>
</html>