let map;
let markers = [];
let directionsService;
let directionsRenderer;

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

function addLocationInput() {
  const locationInputContainer = document.getElementById("location-input-container");
  const inputCount = locationInputContainer.getElementsByTagName("input").length;

  const locationInput = document.createElement("div");
  locationInput.className = "location-input";
  locationInput.innerHTML = `
    <input type="text" placeholder="Enter Location ${inputCount + 1}">
    <button onclick="addMarker(event)">Add Marker</button>
  `;

  locationInputContainer.appendChild(locationInput);
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

      // If there are more than one markers, calculate the route
      if (markers.length > 1) {
        calculateRoute();
      }

      // Center the map on the new marker
      map.setCenter(results[0].geometry.location);

      // Clear the input field
      addressField.value = "";

      // Hide the location input field
      locationInput.style.display = "none";
    } else {
      alert("Geocode was not successful for the following reason: " + status);
    }
  });
}

function calculateRoute() {
  const waypoints = markers.slice(1).map((marker) => ({
    location: marker.getPosition(),
    stopover: true,
  }));

  const request = {
    origin: markers[0].getPosition(),
    destination: markers[markers.length - 1].getPosition(),
    waypoints: waypoints,
    travelMode: google.maps.TravelMode.DRIVING,
  };

  directionsService.route(request, function (result, status) {
    if (status == "OK") {
      directionsRenderer.setDirections(result);
    } else {
      alert("Directions request failed due to " + status);
    }
  });
}
