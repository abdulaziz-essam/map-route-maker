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
      <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Initialize the map and add markers -->
    <script defer>
      let map;
      let markers = [];
      let directionsService;
      let directionsRenderer;
      let autocomplete;

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

        // Initialize the autocomplete object
        autocomplete = new google.maps.places.Autocomplete(
          document.getElementById("autocomplete-input")
        );
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

        // Show the location input field
        locationInput.style.display = "block";

        // Check if geolocation is supported by the browser
        if (navigator.geolocation) {
          // Get the user's current position
          navigator.geolocation.getCurrentPosition(function(position) {
            // Create a new marker at the user's current position
            const marker = new google.maps.Marker({
              position: { lat: position.coords.latitude, lng: position.coords.longitude },
              map: map,
            });
            markers.push(marker);
            map.setCenter(marker.getPosition());
            addressField.value = "";
          }, function(error) {
            console.error(error);
          });
        } else {
          console.error("Geolocation is not supported by this browser.");
        }
      }

      // Add a marker to the map
      function addMarker(event) {
        const button = event.target;
        const locationInput = button.parentNode;
        const input = locationInput.querySelector('input[type="text"]');

        // Show the location input field
        locationInput.style.display = "block";

        // Get the place details from the input field
        const place = autocomplete.getPlace();

        // Check if a place was selected
        if (place.geometry) {
         // Add a marker for the selected place
          const marker = new google.maps.Marker({
            position: place.geometry.location,
            map: map,
          });
          markers.push(marker);
          map.setCenter(place.geometry.location);
          input.value = "";
        } else {
          console.log("No location found for the input: " + input.value);
        }
      }

      // Calculate the route between the markers
      function calculateRoute() {
        const start = markers[0].getPosition();
        const end = markers[markers.length - 1].getPosition();
        const waypoints = [];

        // Add any intermediate markers as waypoints
        for (let i = 1; i < markers.length - 1; i++) {
          waypoints.push({
            location: markers[i].getPosition(),
            stopover: true,
          });
        }

        // Set up the request object
        const request = {
          origin: start,
          destination: end,
          waypoints: waypoints,
          optimizeWaypoints: true,
          travelMode: google.maps.TravelMode.DRIVING,
        };

        // Call the Directions service to calculate the route
        directionsService.route(request, function (result, status) {
          if (status == "OK") {
            // Display the route on the map
            directionsRenderer.setDirections(result);
          } else {
            console.error("Error calculating route:", status);
          }
        });
      }

      // Clear all markers from the map
      function clearMarkers() {
        for (let i = 0; i < markers.length; i++) {
          markers[i].setMap(null);
        }
        markers = [];
        directionsRenderer.setMap(null);
      }

      // Save the route to a file
      function saveRoute() {
        const data = {
          markers: markers.map(function (marker) {
            return {
              lat: marker.getPosition().lat(),
              lng: marker.getPosition().lng(),
            };
          }),
        };

        // Send the data to the server to save it
        axios.post("/create", data)
          .then(function (response) {
            console.log(response.data);
          })
          .catch(function (error) {
            console.error("Error saving route:", error);
          });
      }
    </script>
  </head>
  <body>
    <h3>Custom Driving Directions</h3>

    <!-- Add a button to add location input fields -->
    <button onclick="addLocationInput()">Add Location</button>

    <!-- Add a button to calculate the route -->
    <button onclick="calculateRoute()">Calculate Route</button>

    <!-- Add a button to clear all markers from the map -->
    <button onclick="clearMarkers()">Clear Map</button>

    <!-- Add a button to save the route to a file -->
    <button onclick="saveRoute()">Save Route</button>

    <!-- Add a container for the location input fields -->
    <div id="location-input-container"></div>

    <!-- Add a container for the map -->
    <div id="map"></div>
  </body>
</html>