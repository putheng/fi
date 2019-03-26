<!doctype html>
<html lang="en-US">
  <head>
    <title>Locations Near Me | Google Maps JavaScript API Demo</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/js/style.css?v={{ time() }}">
  </head>
  <body>
    <div id="app" class="content">

      <form action="#" method="post">
        <label for="maxRadius">Find locations within
          <input name="maxRadius" id="maxRadius" type="number" value="1000" min="1" />
        </label>
        <label for="userAddress"> Meters of
          <input name="userAddress" id="userAddress" type="text" placeholder="Your Address" value="Calmette Hospital"/>
        </label>
        <button id="submitLocationSearch">Search</button>
      </form>

      <h2 id="location-search-alert">All Locations</h2>

      <div id="locations-near-you-map"></div>

      <div id="locations-near-you"></div>

      <button onclick="getLocation()">Try It</button>
</div>
<p id="demo"></p>
<br><br><br><br>
      <script defer src="https://maps.googleapis.com/maps/api/js?libraries=geometry&key={{ env('GOOGLE_MAP') }}&callbackx=createSearchableMap"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
      $(document).ready(function(){
          $.get('/data', {}, function(data){
            createSearchableMap(data.data);
          });
      });

    $('#submitLocationSearch').on('click', function(e) {
      e.preventDefault();
        $.get('/data', {}, function(data){
          
          filterLocations(data.data);
        });
    });

var x = document.getElementById("demo");

function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else {
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
}

function showPosition(position) {
  x.innerHTML = "Latitude: " + position.coords.latitude + 
  "<br>Longitude: " + position.coords.longitude;
}
    </script>
      <script src="/js/createSearchableMap.js"></script>
    
  </body>
</html> 