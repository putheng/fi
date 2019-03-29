function createSearchableMap(locations) {
  var bounds = new google.maps.LatLngBounds();
  var mapOptions = {mapTypeId: 'roadmap'};
  var markers = [];
  var infoWindowContent = [];
  var map = new google.maps.Map(document.getElementById('locations-near-you-map'), mapOptions);
  
  map.setTilt(45);
  
  locations.forEach(function(location) {
    markers.push([location.name, location.lat, location.lng]);
    
    infoWindowContent.push(['<div class="infoWindow"><h3 class="font-sr">' + location.name + 
                            '</h3><p>' + location.address + '<br />' + location.city + 
                            ', ' + location.state + '</p><p>Phone ' + 
                            location.phone + '</p></div>']);
  });	    

  var infoWindow = new google.maps.InfoWindow(), marker, i;
  
  // Place the markers on the map
  for (i = 0; i < markers.length; i++) {
    var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
    bounds.extend(position);
    marker = new google.maps.Marker({
      position: position,
      map: map,
      title: markers[i][0]
    });
    
    // Add an infoWindow to each marker, and create a closure so that the current
    // marker is always associated with the correct click event listener
    google.maps.event.addListener(marker, 'click', (function(marker, i) {
      return function() {
        infoWindow.setContent(infoWindowContent[i][0]);
        infoWindow.open(map, marker);
      }
    })(marker, i));

    // Only use the bounds to zoom the map if there is more than 1 location shown
    if (locations.length > 1) {
      map.fitBounds(bounds);
    } else {
      var center = new google.maps.LatLng(locations[0].lat, locations[0].lng);
      map.setCenter(center);
      map.setZoom(30);
    }
  }
}

function filterLocations(allLocations) {
  var userLatLng;
  var geocoder = new google.maps.Geocoder();
  var matters = ($('#matterRange').val() * 1000);
  var killomatter = $('#matterRange').val();
  var maxRadius = parseInt(matters, 10);
  
  if (maxRadius) {
    userLatLng = getLatLngViaHttpRequest(allLocations);
  }

function getLocation() {
    if (navigator.geolocation) {
        return navigator.geolocation.getCurrentPosition(function(position){
          var user_position = {};
          user_position.latitude = position.coords.latitude; 
          user_position.longitude = position.coords.longitude; 

          return user_position;
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}

  function getLatLngViaHttpRequest(allLocations) {

    var address = document.getElementById('userAddress').value.replace(/[^a-z0-9\s]/gi, '');
    var key = 'AIzaSyBJbvmYWb4UyfS1oehM65QQlwst8_JUMgg';

    if(address == 'Your Location'){
      if(navigator.geolocation){
        navigator.geolocation.getCurrentPosition(function(position){

          var addressStripped = position.coords.latitude +','+ position.coords.longitude;

          var request = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' + addressStripped + '&key=' + key;

          $.get(request, function(data) {
            var searchResultsAlert = document.getElementById('location-search-alert');

            if (data.status === "ZERO_RESULTS") {
              searchResultsAlert.innerHTML = "Sorry, '" + address + "' seems to be an invalid address.";
              return;
            }

            var userLatLng = new google.maps.LatLng(data.results[0].geometry.location.lat, data.results[0].geometry.location.lng);
            var filteredLocations = allLocations.filter(isWithinRadius);

            if (filteredLocations.length > 0) {
              createSearchableMap(filteredLocations);
              createListOfLocations(filteredLocations);
              searchResultsAlert.innerHTML = 'Chipotle Locations within ' + killomatter + ' km of ' + address + '';
            } else {
              searchResultsAlert.innerHTML = 'Nothing found!';
              document.getElementById('locations-near-you').innerHTML = '';
              searchResultsAlert.innerHTML = 'Sorry, no Chipotle locations were found within '+ killomatter + ' km of ' + address + '.';
            }

            function isWithinRadius(location) {
              var locationLatLng = new google.maps.LatLng(location.lat, location.lng);
              var distanceBetween = google.maps.geometry.spherical.computeDistanceBetween(locationLatLng, userLatLng);

              return convertMetersToMiles(distanceBetween) <= maxRadius;
            }
          });

        });
      }
    }else{
      var addressStripped = address.split(' ').join('+');

      var request = 'https://maps.googleapis.com/maps/api/geocode/json?address=' + addressStripped + '&key=' + key;

      $.get(request, function(data) {
        var searchResultsAlert = document.getElementById('location-search-alert');

        if (data.status === "ZERO_RESULTS") {
          searchResultsAlert.innerHTML = "Sorry, '" + address + "' seems to be an invalid address.";
          return;
        }

        var userLatLng = new google.maps.LatLng(data.results[0].geometry.location.lat, data.results[0].geometry.location.lng);
        var filteredLocations = allLocations.filter(isWithinRadius);
        
        if (filteredLocations.length > 0) {
          createSearchableMap(filteredLocations);
          createListOfLocations(filteredLocations);
          searchResultsAlert.innerHTML = 'Chipotle Locations within ' + killomatter + ' km of ' + address + '';
        } else {
          searchResultsAlert.innerHTML = 'Nothing found!';
          document.getElementById('locations-near-you').innerHTML = '';
          searchResultsAlert.innerHTML = 'Sorry, no Chipotle locations were found within '+ killomatter + ' km of ' + address + '.';
        }

        function isWithinRadius(location) {
          var locationLatLng = new google.maps.LatLng(location.lat, location.lng);
          var distanceBetween = google.maps.geometry.spherical.computeDistanceBetween(locationLatLng, userLatLng);

          return convertMetersToMiles(distanceBetween) <= maxRadius;
        }
      }); 
    }
  }
}

function convertMetersToMiles(meters) {
  return meters;
  // return (meters * 0.000621371);
}

function createListOfLocations(locations) {
  var locationsList = document.getElementById('locations-near-you');
  
  // Clear any existing locations from the previous search first
  locationsList.innerHTML = '';
  
  locations.forEach( function(location) {
    var specificLocation = document.createElement('div');
    var locationInfo = "<h4>" + location.name + "</h4><p>" + location.address + "</p>" +
                       "<p>"  + location.city + ", " + location.state + "</p><p>" + location.phone + "</p><hr>";
    specificLocation.setAttribute("class", 'location-near-you-box font-sr');
    specificLocation.innerHTML = locationInfo;
    locationsList.appendChild(specificLocation);
  });
}
