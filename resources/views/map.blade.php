<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<title>Stepper</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
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
			<div class="form-filter">
				<p id="location-search-alert"></p>
				<div class="form-group">
					<label class="control-label">Locations</label>
					<select id="userAddress" class="form-control">
						<option value="Your Location">My current location</option>
						
						@foreach($sites as $key => $site)
							<option value="{{ $site->name }}">
								{{ $site->name }}
							</option>
						@endforeach
					</select>
				</div>

				<div class="form-group">
					<label class="control-label">Type</label>
					<select id="type" class="form-control">
						<option value="">All</option>
						<option value="Private">Private</option>
						<option value="Public">Public</option>
						<option value="NGO">NGO</option>
					</select>
				</div>

				<div class="form-group">
					<label class="control-label">Service Type</label>
					<select id="type" class="form-control">
						<option value="">All</option>
						<option value="HIV Test">HIV Test</option>
						<option value="Other Test">Other Test</option>
						<option value="Sth Test">Sth Test</option>
					</select>
				</div>

				<div class="form-group">
					<label class="control-label">
						Filter within <strong id="currentMatter">10</strong>km kilometers
						
					</label>
					<input type="range" id="matterRange" value="10">
				</div>
				<br>
				<div class="form-group">
					<button id="submitLocationSearch" class="btn btn-primary btn-block">Filter Now</button>
				</div>
			</div>
		</div>
		<div class="container-fluit" id="locations-near-you-map"></div>

		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h3 id="resultsLength"></h3>
					<div class="row">
						<div class="col-md-7">
							<div id="locations-near-enable">
@foreach($recommendeds as $recommended)
    <div class="row">
      <div class='col-md-9'>
        <h4>{{ $recommended->name }} <span class="badge">{{ $recommended->type }}</span></h4>
        <p>{{ $recommended->address_desc }}</p>
        <p>{{ $recommended->location_desc }}</p>
        <p>{{ $recommended->phone_num }} {{ $recommended->email }}</p>
      </div>
      <div class="col-md-3">
        <a class='btn booking text-white' href='#'>Book Appointment</a>
      </div>
    </div><hr/>
@endforeach
							</div>
						</div>
						<div class="col-md-4 pull-right">
							<div id="locations-near-disable">
@foreach($notrecommendeds as $notrecommended)
    <div class="row">
      <div class='ol-md-12'>
        <h4>{{ $recommended->name }} <span class="badge">{{ $recommended->type }}</span></h4>
        <p>{{ $recommended->address_desc }}</p>
        <p>{{ $recommended->location_desc }}</p>
        <p>{{ $recommended->phone_num }} {{ $recommended->email }}</p>
      </div>
    </div><hr/>
@endforeach
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type='text/javascript' src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script defer src="https://maps.googleapis.com/maps/api/js?libraries=geometry&key={{ env('GOOGLE_MAP') }}&callbackx=createSearchableMap"></script>
<script src="/js/createSearchableMap.js?v={{ time() }}"></script>
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
    	var type = $('#type').val();

      	e.preventDefault();
        	$.get('/data', {type:type}, function(data){
          
          	filterLocations(data.data);
        });
    });
    </script>
    
  </body>
</body>
</html>