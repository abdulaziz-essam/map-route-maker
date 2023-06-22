<!DOCTYPE html>
<html>
  <head>
    <title>Displaying and Editing Data with Axios</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  </head>
  <body>
    <h1>Data from Axios Request:</h1>
    <div id="data-container"></div>

    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
      // Define the URL for the Axios request
      var url = "/pins";

      // Get the HTML element where the data will be displayed
      var dataContainer = document.getElementById("data-container");

      // Make the Axios request to retrieve the data
      axios.get(url)
        .then(function(response) {
          // Dynamically generate HTML elements for each object in the response data
          response.data.forEach(function(obj, index) {
            // Add the id and name properties to the obj object
            obj.id =  index;


            // Create a div element to hold the data and the edit/delete buttons
            var div = document.createElement("div");
            div.setAttribute("id", obj.id);

            // Create a p element to display the data
            var p = document.createElement("p");
            p.setAttribute("name", obj.locations.name);
            p.innerHTML = JSON.stringify(obj, null, 2);

            // Create a button element to edit the data
            var editBtn = document.createElement("button");
            editBtn.innerHTML = "Edit";


// Store the locations array as a JSON string in a custom attribute
editBtn.setAttribute("data-locations", JSON.stringify(obj));
editBtn.setAttribute("data-id", obj.id);
            editBtn.addEventListener("click", function() {
  // Get the name, latitude, and longitude of the edited data
  var data = this.getAttribute("data-locations");
  let id = this.getAttribute("data-id");
console.log(id)

  // Construct an object with the data to be passed


  // Store the data in local storage
  localStorage.setItem("editData", JSON.stringify(id));

  // Navigate to the edit page
  window.location.href = "/";
});

            // Create a button element to delete the data
            var deleteBtn = document.createElement("button");
            deleteBtn.innerHTML = "Delete";
            deleteBtn.setAttribute("data-id", obj.id);
            deleteBtn.addEventListener("click", function() {
              var dataId = this.getAttribute("data-id");
              axios.delete(url+"/"+dataId)
              .then(function(response) {
                div.remove();
              })
              .catch(function(error) {
                console.log(error);
              });
            });

            // Append the p element, edit button, and delete button to the div element
            div.appendChild(p);
            div.appendChild(editBtn);
            div.appendChild(deleteBtn);

            // Append the div element to the data container
            dataContainer.appendChild(div);
          });
        })
        .catch(function(error) {
          console.log(error);
        });
    </script>
  </body>
</html>
