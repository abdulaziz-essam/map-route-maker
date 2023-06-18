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
            // Create a div element to hold the data and the edit button
            var div = document.createElement("div");
            div.setAttribute("id", "data-" + index);

            // Create a p element to display the data
            var p = document.createElement("p");
            p.innerHTML = JSON.stringify(obj, null, 2);

            // Create a button element to edit the data
            var editBtn = document.createElement("button");
            editBtn.innerHTML = "Edit";
            editBtn.setAttribute("data-index", index);
            editBtn.addEventListener("click", function() {
              // Get the new value of the data from the user
              var newData = prompt("Enter the new data:");

              // Get the index of the edited data
              var dataIndex = this.getAttribute("data-index");

              // Update the data on the server using an Axios POST request
              axios.post(url, { index: dataIndex, data: newData })
                .then(function(response) {
                  // Set the innerHTML of the p element to the updated value
                  p.innerHTML = JSON.stringify(response.data, null, 2);
                })
                .catch(function(error) {
                  console.log(error);
                });
            });

            // Append the p element and the edit button to the div element
            div.appendChild(p);
            div.appendChild(editBtn);

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
