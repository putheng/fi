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
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<template v-if="step == 0">
				<h3 class="text-center">{{ $term->getHeading() }}</h3>
			</template>
			<template v-if="step == getRecommenStep">
				<h3 class="text-center font-sr custom-style">{{ $recommend->getHeading() }}</h3>
			</template>
			<template v-for="(question, index) in questions">
				<h3 class="text-center font-sr" v-if="step == (index + 1)">@{{ question.header }}</h3>
			</template>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<ul class="breadcrumb">
				<template>
					<li :class="welcomeClass">
						<a @click.prevent="getWelcome" href="#" class="font-sr">{{ $term->getBradcume() }}</a>
					</li>
				</template>
				<template v-for="(question, index) in questions">
					<li :class="getStep(index)">
						<a @click.prevent="currentStep(index)" href="#" class="font-sr">
							{{ question.<?php echo __('page.sub_question') ?> }}
						</a>
					</li>
				</template>
				<template>
					<li :class="recommendationClass">
						<a href="#" class="font-sr">{{ $recommend->getTitle() }}</a>
					</li>
				</template>
				<li :class="resultCompletedClass" class="alway-show">
					<a href="#" class="font-sr">{{ __('page.results') }}</a>
				</li>
			</ul>
		</div>
	</div>
</div>
	<transition name="slide" >
		<template v-if="step === 0">
			<div class="main container">
				<div class="row">
					<div class="col-sm-3 col-xs-6 col-md-offset-1">
						<img src="/images/step-6.png" class="img-responsive hide-mobile">
					</div>
					<div class="col-sm-7 col-xs-6">
						<h4>{{ $term->getTitle() }}</h4>
						<p class="font-sr">
							{{ $term->getSubtitle() }}
						</p>
						<br>
						<p>
							<input id="term" type="checkbox" v-model="term">
							<label for="term"> {{ $term->getTerm() }}</label>
						</p>
						<p>
							<input @click="acceptTerm" type="button" value="OK, LET'S START" class="btn btn-primary">
						</p>
						<p><br>
							<i>{{ $term->getNote() }}</i>
						</p>
						<br>
					</div>
				</div>
			</div>
		</template>
	</transition>
		@foreach($questions as $key => $question)
			<transition name="slide" >
				<template v-if="step === {{ ($key+1) }}">
				<div class="main container">
					<div class="row">
						<div class="col-sm-3 col-md-offset-1">
							<img src="{{ optional($question->image)->path() }}" class="img-responsive hide-mobile">
						</div>
						<div class="col-sm-7">
							<br>
							<p class="font-sr title">{{ $question->{__('page.title')} }}</p>
							<br>
							
							<div class="list-group">
								@if($question->type == 1)
									@foreach($question->answers as $index => $answer)
										<input type="radio" 
											id="{{ $answer->id }}"
											type="radio"
											value="{{ $answer->point }}"
											v-model="registration.question<?php echo ($key+1); ?>"
										 />
										<label  for="{{ $answer->id }}" @click="next" class="list-group-item font-sr">
											<div class="tick"></div><div class="answer">
											{{ $answer->{__('page.answer_title')} }}
											</div>
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
										<label  for="{{ $answer->id }}" class="list-group-item font-sr">
											<div class="tick"></div><div class="answer">
												{{ $answer->{__('page.answer_title')} }}
											</div>
										</label>
									@endforeach
									<br>
									<a href="#" @click.prevent="nextContinue" class="btn btn-primary font-sr pull-right">
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
			<template v-if="step == getRecommenStep">
				<div class="main container">
					<div class="row">
						<div class="col-sm-3 col-xs-6 col-md-offset-1">
							<img src="/images/step-6.png" class="img-responsive">
						</div>
						<div class="col-sm-7 col-xs-6">
							<br>
							<p class="font-sr title">{{ $recommend->getDescription() }}</p>
							<br>
							<a href="#" @click.prevent="completeRecommanded" class="btn btn-primary font-sr pull-right">
								{{ __('page.next') }}
							</a>
						</div>
					</div>
				</div>
			</template>
		</transition>
		<transition name="slide" >
			<template v-if="step == getResultStep">
				<div class="main container">
					<div class="row">
						<div class="col-sm-3 col-xs-6 col-md-offset-1">
							<img :src="'/uploads/'+ answer.image.path" class="img-responsive">
						</div>
						<div class="col-sm-7 col-xs-6">
							<br>
							<p class="font-sr title">{{ answer.<?php echo __('page.result'); ?> }}</p>
							<br>
							<p class="font-sr">{{ answer.<?php echo __('page.description'); ?> }}</p>
						</div>
					</div>
				</div>
			</template>
		</transition>
</div>

<script src="https://unpkg.com/vue/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue-resource@1.5.1"></script>
<script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script type='text/javascript' src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

<script>
	new Vue({
	  el: '#wrap',
	  data: () => ({
	      step: 0,
	      term: false,
	      isActive: false,
	      questions: [],
	      answers: [],
	      answer: {},
	      registration: {
	      	@foreach($questions as $keys => $question)
	      		@if($question->type == 1)
question{{ $keys + 1 }}: null,
	      		@else
question{{ $keys + 1 }}: [],
		        @endif
	        @endforeach
	}
	  }),
	  methods:{
	  	getWelcome(){
	  		this.step = 0
	  	},
	  	acceptTerm(){
	  		if(this.term){
	  			this.step++
	  		}
	  	},
	  	nextStep(){
	  		if(!this.term){
	  			return
	  		}
	  		this.step++
	  		// 	this.$http.post('{{ route('ass.save') }}').then(response => {

			//     this.someData = response.body;

			// 	}, response => {

			// })
	  	},
	  	next(){

	  		if(this.step <= this.stepLength){
	  			setTimeout(() => {
				   this.nextStep()
				}, 300)
	  		}

	  	},
	  	completeRecommanded(){
	  		this.step = this.getResultStep

	    	let values = Object.values(this.registration).filter((key) => {
	    		return key !== null
	    	})

			var total = [].concat
	    			.apply([], values)
	    			.reduce(function(a, b) { return +a + +b });

	    	this.answer = this.answers.filter((key, item) => {
	    		return total >= key.from && total <= key.to
	    	})[0]

	    	console.log(this.step)
	  	},
	  	nextContinue(){

	  		if(this.registration["question"+ this.step].length == 0){
	  			return
	  		}

	  		if(this.step <= this.stepLength){
	  			setTimeout(() => {
				    this.nextStep()
				}, 300)
	  		}

	  		if(this.step == this.questionLength){
	  			//save
	  		}

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
	    	if(!this.term){
	    		return
	    	}

	    	const id = index + 1

	    	// console.log(this.registration["question"+ id])

	    	if(id === 2 && this.registration.question1.length === 0){
	    		return
	    	}else if(id === 3 && this.registration.question2.length === 0){
	    		return
	    	}else if(id === 4 && this.registration.question3.length === 0){
	    		return
	    	}else if(id === 5 && this.registration.question4.length === 0){
	    		return
	    	}

	    	return this.step = id

	    }
	  },
	  mounted(){
	  	this.questions = <?php echo $questions->toJson() ?>

	  	this.answers = <?php echo $results->toJson() ?>

	  },
	  computed: {
	  	getResultStep(){
	  		return this.questions.length + 2
	  	},
	  	getRecommenStep(){
	  		return this.questions.length + 1
	  	},
	  	recommendationClass(){
	  		if(this.step == this.getRecommenStep){
	  			return 'active'
	  		}else if(this.step > this.getRecommenStep){
	  			return 'completed'
	  		}
	  	},
	  	welcomeClass(){
	  		if(this.step == 0){
	  			return 'active'
	  		}else{
	  			return 'completed'
	  		}
	  	},
	  	stepLength(){
	  		return this.step <= this.questions.length ? this.step : this.questions.length
	  	},
	  	questionLength(){
	  		return this.questions.length
	  	},
	  	resultCompletedClass(){
	  		if(this.step == this.getResultStep){
	  			return 'completed'
	  		}
	  	}
	  }
	})
</script>
</body>
</html>