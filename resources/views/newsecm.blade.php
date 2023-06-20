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
    <label for="data-id">ID:</label>
    <input type="text" id="data-id">
    <label for="data-name">Name:</label>
    <input type="text" id="data-name">
    <label for="data-input">Data:</label>
    <input type="text" id="data-input">
    <button id="submit-btn">Save Changes</button>
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
    // Get the ID and name values from the localStorage object
    var editedData = JSON.parse(localStorage.getItem("editedData"));
    var id = editedData.id;
    var name = editedData.name;

    // Pre-fill the id and name input fields with the values passed from the previous page
    dataId.value = id;
    dataName.value = name;

    // Fetch the pin data from the server using an Axios GET request
    axios.get("/pins/"+id)
      .then(function(response) {
        var pinData = response.data;

        // Pre-fill the input fields with the pin data
        dataInput.value = pinData.data;

        // Initialize the map centered on the pin location
        var map = new google.maps.Map(document.getElementById("map"), {
          center: { lat: pinData.lat, lng: pinData.lng },
          zoom: 12,
        });

        // Add a marker to the map at the pin location
        var marker = new google.maps.Marker({
          position: { lat: pinData.lat, lng: pinData.lng },
          map: map,
        });
      })
      .catch(function(error) {
        console.log(error);
      });

    // Add an event listener to the submit button
    var submitBtn = document.getElementById("submit-btn");
    submitBtn.addEventListener("click", function() {
      // Get the updated data from the input field
      var updatedData = dataInput.value;

      // Update the pin data on the server using an Axios PUT request
      axios.put("/pins/"+id, { data: updatedData })
        .then(function(response) {
          // Navigate back to the original page
          window.location.href = "/";
        })
        .catch(function(error) {
          console.log(error);
        });
    });
  </script>
</body>
</html>
