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
    <label for="data-latitude">Latitude:</label>
    <input type="text" id="data-latitude">
    <label for="data-name">Name:</label>
    <input type="text" id="data-name">
    <label for="data-input">Data:</label>
    <input type="text" id="data-input">
    <!-- <button id="submit-btn">Save Changes</button> -->
  </div>

  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <!-- Load the Maps JavaScript API -->
  <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBKLSpPTjBW85app41q294VhRivfau8inQ&libraries=places,directions&callback=initMap"
      type="text/javascript"></script>
  <script>
    // Get the HTML elements for the form and the input field
    var editForm = document.getElementById("edit-form");
    var dataInput = document.getElementById("data-input");
    var dataId = document.getElementById("data-id");
    var dataName = document.getElementById("data-name");
    var dataLatitude = document.getElementById("data-latitude");
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

      // Retrieve the editedData object from localStorage
      var editedData = JSON.parse(localStorage.getItem("editedData"));
console.log(editedData)
      // Access the properties of the editedData object
      var locationData = editedData.location;
      var latitude = locationData.latitude;
      var longitude = locationData.longitude;
      var name = locationData.name;

      // Set the value of the latitude and longitude input fields
      dataLatitude.value = latitude;
      dataName.value = name;
      dataInput.value = longitude;

      // Set the value of the ID input field
      dataId.value = editedData.id;

  </script>
</body>
</html>
