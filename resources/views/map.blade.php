<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<title>Stepper</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://www.jqueryscript.net/demo/Highly-Customizable-Range-Slider-Plugin-For-Bootstrap-Bootstrap-Slider/dist/css/bootstrap-slider.css" rel="stylesheet" type="text/css">
	<!--[if lt IE 9]>
	<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="/css/style.css?v={{ time() }}">
</head>
<body >
<div id="wrap">
	<nav class="navbar navbar-default navbar-fixed-top navbar-with-search text-white">
		<div class="container">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<div class="navbar-header">
						<a class="navbar-brand" href="#">
							<img 
								src="https://getbootstrap.com/docs/4.3/assets/brand/bootstrap-solid.svg" 
								width="30" height="30" class="d-inline-block align-top" alt=""
							>
							Logo
						</a>
					</div>
					<form class="navbar-form">
						<div class="dropdown pull-left">
							<a class="dropdown-toggle" 
								id="language-button"
								data-toggle="dropdown"
								aria-haspopup="true"
								aria-expanded="true">
								Language
								<span class="glyphicon glyphicon-globe" 
									aria-hidden="true"></span>
							</a>
							<ul class="dropdown-menu dropdown-menu-right" 
								aria-labelledby="language-button">
								<li>
									<a href="/lang/hi" class="font-sr">
										{{ __('page.kh') }}
									</a>
								</li>
								<li>
									<a href="/lang/en">English</a>
								</li>

							</ul>
						</div>
					</form>
				</div>
			</div>
		</div>
	</nav>
<br>
	<div class="map-wrapper">
		<div class="innerHTML">
			<div class="container">
				<div class="row">
					<div class="col-md-4 pull-right">
						<div class="form-filter">
							<div class="form-group">
								<label class="control-label">Locations</label>
								<select class="form-control">
									<option>My current location</option>
									<option>Phnom Penh</option>
									<option>Province</option>
								</select>
							</div>

							<div class="form-group">
								<label class="control-label">Type</label>
								<select class="form-control">
									<option>Private</option>
									<option>Public</option>
									<option>NGO</option>
								</select>
							</div>

							<div class="form-group">
								<label>
									Kilometer from you
									<strong id="currentMatter">1</strong>km
								</label>
								<input type="range" id="matterRange" value="1">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container-fluit" id="locations-near-you-map"></div>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type='text/javascript' src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script defer src="https://maps.googleapis.com/maps/api/js?libraries=geometry&key={{ env('GOOGLE_MAP') }}&callbackx=createSearchableMap"></script>
<script>
function formatNumber(num) {
  return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}
$('#matterRange').on('change', function(){
	var currentMatter = $(this).val();
	var formated = formatNumber(currentMatter);

	$('#currentMatter').text(currentMatter);
});
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
</body>
</html>