
<!DOCTYPE html>
<html >
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Preview</title>

  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" />
  <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="/assets/css/custom.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body >
  
<div id="app">
<div class="app-model">
	<div class="app-model-content">


      <transition-group tag="div" class="img-slider" name="slide">
    	
      </transition-group>


		<p class="title">1. How long have you been studying here?</p>
		<ul class="model-list-group">
			<li>
				<input class="checkbox" id="answera" type="radio" name="answer" value="1">
				<label for="answera">
					<span class="gat">A</span>
					<span>Under 1 year</span>
				</label>
			</li>
			<li>
				<input class="checkbox" id="answerb" type="radio" name="answer" value="1">
				<label for="answerb">
					<span class="gat">A</span>
					<span>Under 1 year</span>
				</label>
			</li>
			<li>
				<input class="checkbox" id="answerc" type="radio" name="answer" value="1">
				<label for="answerc">
					<span class="gat">A</span>
					<span>Under 1 year</span>
				</label>
			</li>
			<li>
				<input class="checkbox" id="answer" type="radio" name="answer" value="1">
				<label for="answer">
					<span class="gat">A</span>
					<span>Under 1 year</span>
				</label>
			</li>
		</ul>
	</div>
</div>
  
<nav class="navbar fixed-bottom navbar-toggleable-sm navbar-inverse bg-inverse">
    <div class="container d-flex flex-row flex-md-nowrap flex-wrap">
         <ul class="navbar-nav">
            <li class="nav-item">
                <div class="nav-item">0 of 7 answered</div>
            </li>
        </ul>

	    <div class="d-flex ml-auto">
	         <ul class="navbar-nav">
	                
	            <li class="nav-item">
	                <a class="nav-link" href="#">Previous</a>
	            </li>

	            <li class="nav-item">
	                <a class="nav-link" href="#">Next</a>
	            </li>
	        </ul>
	    </div>

	    <div class="hidden-md-up w-100"></div>
    </div>
</nav>
</div>
<script src="https://unpkg.com/vue/dist/vue.js"></script>
<script src="https://unpkg.com/vuetify/dist/vuetify.min.js"></script>
</body>
</html>
