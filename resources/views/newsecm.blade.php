<!DOCTYPE html>
<html>
<head>
  <title>Edit Data</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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

  <!-- <meta name="csrf-token" content="YOUR_CSRF_TOKEN_HERE"> -->

  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <!-- Load the Maps JavaScript API -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBKLSpPTjBW85app41q294VhRivfau8inQ&libraries=places,directions&callback=initMap" type="text/javascript"></script>
  <script>
    // Retrieve the data object from localStorage

    let editdata;
let editdataStr = localStorage.getItem("editData");



  let editDataLoc = editdata.locations;
  console.log(editDataLoc);

  // Get the HTML elements for the form and the input field
  var editForm = document.getElementById("edit-form");
  var locationInputs = document.getElementById("location-inputs");

  // Loop through the locations array and create input fields for each location



    let map;
    let markers = [];
    let directionsService;
    let directionsRenderer;
    let autocomplete;
let longitudeData=document.getElementById("data-latitude")
    // Initialize and add the map
    function initMap() {

    // Use the CSRF token in your POST requests
    axios.post('/show', { id: "1" }, {
      headers: {
        'X-CSRF-TOKEN': csrfToken
      }
    })
    .then(function (response) {
      console.log(response.data);

      var locations = response.data.locations;
      var locationInputs = document.getElementById("location-inputs");
      locationInputs.innerHTML = '';

      locations.forEach(function(locationData, index) {
        var latitude = locationData.latitude;
        var longitude = locationData.longitude;
        var name = locationData.name;

        var locationFieldsHTML = `
          <div class="location-fields" data-index="${index}">
            <label for="data-latitude-${index}">Latitude:</label>
            <input type="text" id="data-latitude-${index}" value="${latitude}">
            <label for="data-name-${index}">Name:</label>
            <input type="text" id="data-name-${index}" value="${name}">
            <label for="data-longitude-${index}">Longitude:</label>
            <input type="text" id="data-longitude-${index}" value="${longitude}">
          </div>
        `;

        locationInputs.insertAdjacentHTML("beforeend", locationFieldsHTML);
      });

    })
    .catch(function (error) {
      console.log(error);
    });
  }
  .catch(function (error) {
    console.log(error);
  });
}
      console.log("Initializing map...");
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

      // Create the Autocomplete object and link it to the search input field
    //   var dataLatitude = document.getElementById("data-latitude-0");
    //   autocomplete = new google.maps.places.Autocomplete(dataLatitude);


    //   autocomplete.bindTo("bounds", map);

      // When the user selects a place from the dropdown list, update the map center and zoom level
    //   autocomplete.addListener("place_changed", function() {
    //     var place = autocomplete.getPlace();
    //     if (!place.geometry) {
    //       console.log("Place not found: " + place.name);
    //       return;
    //     }
    //     map.setCenter(place.geometry.location);
    //     map.setZoom(15);
    //   });
    // }

    // Add a marker to the map when the "Add Marker" button is clicked
    var addMarkerBtn = document.getElementById("add-marker-btn");
    addMarkerBtn.addEventListener("click", function() {
      console.log("Adding marker...");
      // Get the latitude and longitude values from the input fields
      var latitude = parseFloat(document.getElementById("data-latitude-0").value);
      var longitude = parseFloat(document.getElementById("data-longitude-0").value);

      console.log("Latitude: " + latitude);
      console.log("Longitude: " + longitude);

      // Create a new marker and add it to the map
      var marker = new google.maps.Marker({
        position: { lat: latitude, lng: longitude },
        map: map,
        title: "Marker"
      });

      // Add the marker to the markers array
      markers.push(marker);

      // Center the map on the new marker
      map.setCenter(marker.getPosition());
    });



  </script>
</body>
</html>
