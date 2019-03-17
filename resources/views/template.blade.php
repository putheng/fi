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
	<nav class="navbar navbar-default navbar-fixed-top navbar-with-search">
		<div class="container">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<div class="navbar-header">
						<a class="navbar-brand" href="#">
							<img 
								src="https://getbootstrap.com/docs/4.3/assets/brand/bootstrap-solid.svg" 
								width="30" height="30" class="d-inline-block align-top" alt=""
							>
							Stepper
						</a>
					</div>
					<form class="navbar-form">
						<div class="dropdown pull-left">
							<a class="dropdown-toggle" 
								id="language-button"
								data-toggle="dropdown"
								aria-haspopup="true"
								aria-expanded="true">
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
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<ul class="breadcrumb">
				<template v-for="(question, index) in questions">
					<li :class="getStep(index)">
						<a @click.prevent="currentStep(index)" href="#" class="font-sr">
							{{ question.<?php echo __('page.sub_question') ?> }}
						</a>
					</li>
				</template>
				<li :class="resultCompletedClass" class="alway-show">
					<a href="#" class="font-sr">{{ __('page.results') }}</a>
				</li>
			</ul>
		</div>
	</div>
</div>

		@foreach($questions as $key => $question)
			<transition name="slide" >
				<template v-if="step === {{ ($key+1) }}">
				<div class="main container">
					<div class="row">
						<div class="col-sm-3 col-md-offset-1">
							<img src="{{ optional($question->image)->path() }}" class="img-responsive">
						</div>
						<div class="col-sm-7">
							<br>
							<p class="font-sr title">{{ $question->{__('page.title')} }}</p>
							<br>
							
							<div class="list-group">
								@if($question->type == 1)
									@foreach($question->answers as $index => $answer)
										<input type="checkbox" 
											id="{{ $answer->id }}"
											type="checkbox"
											value="{{ $answer->point }}"
											v-model="registration.question<?php echo ($key+1); ?>"
										 />
										<label  for="<?php echo $answer->id; ?>" @click="next" class="list-group-item font-sr">
											{{ $answer->{__('page.answer_title')} }}
										</label>
									@endforeach
								@else
									@foreach($question->answers as $index => $answer)
										<input type="checkbox" 
											id="{{ $answer->id }}"
											type="radio"
											value="{{ $answer->point }}"
											v-model="registration.question{{ ($key+1) }}"
										 />
										<label  for="{{ $answer->id }}" class="list-group-item font-sr">{{ $answer->{__('page.answer_title')} }}</label>
									@endforeach
									<br>
									<a href="#" @click="next" class="btn btn-primary font-sr pull-right">
										{{ __('page.next') }}
									</a>
								@endif
							</div>
							<br><br><br>
						</div>
					</div>
					</div>
				</template>
			</transition>
		<?php endforeach; ?>
		<transition name="slide" >
			<template v-if="step === 6">
				<div class="main container">
					<div class="row">
						<div class="col-sm-3 col-xs-6 col-md-offset-1">
							<img :src="'/uploads/'+ answer.image.path" class="img-responsive">
						</div>
						<div class="col-sm-7 col-xs-6">
							<br>
							<p class="font-sr title">{{ answer.<?php echo __('page.result'); ?> }}</p>
							<br>
						</div>
					</div>
				</div>
			</template>
		</transition>
</div>

<script src="https://unpkg.com/vue/dist/vue.js"></script>
<script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script type='text/javascript' src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

<script>
	new Vue({
	  el: '#wrap',
	  data: () => ({
	      step: 1,
	      isActive: false,
	      questions: [],
	      answers: [],
	      answer: {},
	      registration: {
	        question1: [],
	        question2: [],
	        question3: [],
	        question4: [],
	        question5: [],
	      }
	  }),
	  methods:{
	  	next(){
	  		if(this.step <= this.stepLength){
	  			setTimeout(() => {
				   this.step++
				}, 300)
	  		}

	    	let values = Object.values(this.registration).filter((key) => {
	    		return key !== null
	    	})

			var total = [].concat
	    			.apply([], values)
	    			.reduce(function(a, b) { return +a + +b });

	    	this.answer = this.answers.filter((key, item) => {
	    		return total >= key.from && total <= key.to
	    	})[0]
	  	},
	  	previous(){
	  		if(this.step >= 1){
	  			this.step--
	  		}
	  	},
	    submit() {
	    	let values = Object.values(this.registration).filter((key) => {
	    		return key !== null
	    	})

	    	console.log(values)

	    	var total = [].concat
	    			.apply([], values)
	    			.reduce(function(a, b) { return +a + +b });

	    	this.answer = this.answers.filter((key, item) => {
	    		return total >= key.from && total <= key.to
	    	})[0]
	    },
	    getStep(id){
	    	let step = +id + 1
	    	
	    	if(this.step === step){
	    		return 'active'
	    	}else if(this.step > step){
	    		return 'completed'
	    	}else{
	    		return ''
	    	}
	    },
	    currentStep(index){
	    	const id = index + 1

	    	// console.log(this.registration.question + id)

	    	return this.step = id

	    }
	  },
	  mounted(){
	  	this.questions = <?php echo $questions->toJson() ?>

	  	this.answers = <?php echo $results->toJson() ?>

	  },
	  computed: {
	  	stepLength(){
	  		return this.step <= this.questions.length ? this.step : this.questions.length
	  	},
	  	questionLength(){
	  		return this.questions.length
	  	},
	  	resultCompletedClass(){
	  		let length = this.questions.length + 1

	  		if(this.step == length){
	  			return 'completed'
	  		}
	  	}
	  }
	})
</script>
</body>
</html>