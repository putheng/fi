new Vue({
  el: '#wrap',
  data: () => ({
      step: 1,
      isActive: false,
      questions: [],
      answers: [],
      answer: {},
      registration: {
      	@foreach($questions as $keys => $question)
	        question{{ $keys + 1 }}: [],
        @endforeach
      }
  }),
  methods:{
  	next(){
  		if(this.step == 1 && this.registration.question1.length !== 0){
  			return
  		}else if(this.step == 2 && this.registration.question1.length !== 2){
  			return
  		}else if(this.step == 3 && this.registration.question1.length !== 3){
  			return
  		}else if(this.step == 4 && this.registration.question1.length !== 4){
  			return
  		}else if(this.step == 5 && this.registration.question1.length !== 5){
  			return
  		}

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