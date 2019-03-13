
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
<template v-for="(question, index) in questions">
	<template v-if="step == question.sort_order">
		<div class="app-modelx" :key="index">
			<div class="app-model-contentx">
				<p class="title font-muol">
					{{ question.sort_order }}
					{{ question.titleKh }}
				</p>
				<ul class="model-list-group">
					<template v-for="(answer, key) in question.answers">
						<li :key="answer.id">
							<input class="checkbox" :id="answer.id" type="radio" name="answer" value="1">
							<label :for="answer.id" @click="next">
								<span class="gat">A</span>
								<span class="font-sr">{{ answer.title }}</span>
							</label>
						</li>
					</template>
				</ul>
			</div>
		</div>
	</template>
</template>

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
<script>
	new Vue({
	  el: '#app',
	  data: () => ({
	      step: 1,
	      isActive: false,
	      questions: [],
	      answers: [],
	      answer: {},
	      registration: {
	        question1: null,
	        question2: null,
	        question3: [],
	        question4: null,
	        question5: [],
	      }
	  }),
	  methods:{
	  	next(){
	  		this.step++
	  	},
	    submit() {
	    	this.isActive = true
	    	let values = Object.values(this.registration).filter((key) => {
	    		return key !== null
	    	})

	    	var total = [].concat
	    			.apply([], values)
	    			.reduce(function(a, b) { return +a + +b });

	    	this.answer = this.answers.filter((key, item) => {
	    		return total >= key.from && total <= key.to
	    	})[0]
	    }
	  },
	  mounted(){
	  	this.questions = <?php echo $questions->toJson() ?>

	  	this.answers = <?php echo $results->toJson() ?>
	  }
	})
</script>
</body>
</html>
