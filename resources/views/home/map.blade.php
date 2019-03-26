<!doctype html>
<html lang="en-US">
  <head>
    <title>Locations Near Me | Google Maps JavaScript API Demo</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/js/style.css?v={{ time() }}">
  </head>
  <body>
    <div class="content">

      <form action="#" method="post">
        <label for="maxRadius">Find locations within
          <input name="maxRadius" id="maxRadius" type="number" value="10" min="1" />
        </label>
        <label for="userAddress"> miles of
          <input name="userAddress" id="userAddress" type="text" placeholder="Your Address" value="Calmette Hospital"/>
        </label>
        <button id="submitLocationSearch">Search</button>
      </form>

      <h2 id="location-search-alert">All Locations</h2>

      <div id="locations-near-you-map"></div>

      <div id="locations-near-you"></div>

<br><br><br><br>
      <script defer src="https://maps.googleapis.com/maps/api/js?libraries=geometry&key={{ env('GOOGLE_MAP') }}&callback=createSearchableMap"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="/js/allLocations.js"></script>
      <script src="/js/createSearchableMap.js"></script>
    </div>
  </body>
</html> 