<!DOCTYPE html>
<!--
 @license
 Copyright 2019 Google LLC. All Rights Reserved.
 SPDX-License-Identifier: Apache-2.0
-->
<html>
  <head>
    <title>Custom Driving Directions</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

    <!-- Add your style rules here -->
    <style>
      body {
        background-color: #f0f0f0;
        font-family: Arial, sans-serif;
      }

      h3 {
        color: blue;
      }

      #map {
        height: 400px;
      }

      .location-input {
        margin-bottom: 10px;
      }

      .location-input input[type="text"] {
        width: 200px;
      }
    </style>

    <!-- Load the Maps JavaScript API -->
    <script defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBKLSpPTjBW85app41q294VhRivfau8inQ&libraries=places,directions&callback=initMap"
      type="text/javascript"></script>

    <!-- Initialize the map and add markers -->
    <script defer>
      let map;
      let markers = [];
      let directionsService;
      let directionsRenderer;

      // Initialize and add the map
      function initMap() {
        directionsService = new google.maps.DirectionsService();
        directionsRenderer = new google.maps.DirectionsRenderer();

        // The location of the map center
        const myLatLng = { lat: -25.363, lng: 131.044 };
        // The map, centered at myLatLng
        map = new google.maps.Map(document.getElementById("map"), {
          zoom: 4,
          center: myLatLng,
        });

        directionsRenderer.setMap(map);
      }

 // Add a location input field
 function addLocationInput() {
  const locationInputContainer = document.getElementById("location-input-container");
  const inputCount = locationInputContainer.getElementsByTagName("input").length;

  const locationInput = document.createElement("div");
  locationInput.className = "location-input";
  locationInput.innerHTML = `
    <input type="text" placeholder="Enter Location ${inputCount + 1}">
    <button onclick="addMarker(event)">Add Marker</button>
    <button onclick="getCurrentLocation(event)">Use Current Location</button>
  `;

  locationInputContainer.appendChild(locationInput);

  // Get the input field
  const input = locationInput.getElementsByTagName("input")[0];

  // Initialize the autocomplete object
  const autocomplete = new google.maps.places.Autocomplete(input);

  // Add a listener for when a place is selected
  google.maps.event.addListener(autocomplete, "place_changed", function () {
    const place = autocomplete.getPlace();
    if (place.geometry) {
      // Add a marker for the selected place
      const marker = new google.maps.Marker({
        position: place.geometry.location,
        map: map,
      });
      markers.push(marker);
      map.setCenter(place.geometry.location);
      input.value = "";
      locationInput.style.display = "none";
    } else {
      console.log("No location found for the input: " + input.value);
    }
  });
}
// Get the user's current location and add it to the map
function getCurrentLocation(event) {
  const button = event.target;
  const locationInput = button.parentNode;
  const addressField = locationInput.querySelector('input[type="text"]');

  // Check if geolocation is supported by the browser
  if (navigator.geolocation) {
    // Get the user's current position
    navigator.geolocation.getCurrentPosition(function(position) {
      // Create a new marker at the user's current position
      const marker = new google.maps.Marker({
        position: { lat: position.coords.latitude, lng: position.coords.longitude },
        map: map,
      });

      // Add the marker to the markers array
      markers.push(marker);

      // Center the map on the new marker
      map.setCenter(marker.getPosition());

      // Clear the input field
      addressField.value = "";

      // Hide the location input field
      locationInput.style.display = "none";
    }, function(error) {
      alert(`Unable to retrieve your location: ${error.message}`);
    });
  } else {
    alert("Geolocation is not supported by your browser");
  }
}

function addMarker(event) {
  const button = event.target;
  const locationInput = button.parentNode;
  const addressField = locationInput.querySelector('input[type="text"]');

  // Get the location from the input field
  const address = addressField.value.trim();

  // Check if the input field is empty
  if (address === "") {
    alert("Please enter a location");
    return;
  }

  // Use Geocoder to get location information from address
  const geocoder = new google.maps.Geocoder();
  geocoder.geocode({ address: address }, function (results, status) {
  if (status === "OK") {
    // Add a marker at the location
    const marker = new google.maps.Marker({
      position: results[0].geometry.location,
      map: map,
    });

    // Add the marker to the markers array
    markers.push(marker);

    // Center the map on the new marker
    map.setCenter(results[0].geometry.location);

    // Clear the input field
    addressField.value = "";

    // Hide the location input field
    locationInput.style.display = "none";

    // Pass the results array to calculateRoute
    calculateRoute(results, address);
  } else {
    alert("Geocode was not successful for the following reason: " + status);
  }
});
}

function calculateRoute(markers) {
  if (markers.length < 2) {
    alert("Please add at least two locations");
    return;
  }

  const waypoints = [];
  for (let i = 1; i < markers.length - 1; i++) {
    waypoints.push({
      location: markers[i].getPosition(),
      stopover: true,
    });
  }

  const origin = markers[0].getPosition();
  const destination = markers[markers.length - 1].getPosition();

  const request = {
    origin: origin,
    destination: destination,
    waypoints: waypoints,
    optimizeWaypoints: true,
    travelMode: google.maps.TravelMode.DRIVING,
  };

  directionsService.route(request, function (result, status) {
    if (status == "OK") {
      directionsRenderer.setDirections(result);
    }
  });
}

      // Send a POST request to the server-side script
function saveLocationToDB(name, latitude, longitude) {
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "/create");
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.onload = function() {
    if (xhr.status === 200) {
      console.log("Location saved to database");
    } else {
      console.log("Error saving location to database");
    }
  };
  xhr.send(JSON.stringify({ name: name, latitude: latitude, longitude: longitude }));
}
    </script>
  </head>
  <body>
    <h3>Custom Driving Directions</h3>
    <div>
  <button onclick="addLocationInput()">Add Location</button>
  <div id="location-input-container"></div>
  <button onclick="calculateRoute(markers)">Calculate Route</button>
</div>
<div id="map"></div>
<div id="directions-panel"></div>
  </body>
</html>
