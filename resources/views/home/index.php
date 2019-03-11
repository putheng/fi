
<!DOCTYPE html>
<html>
<head>
	<title>Multi-step form, Vuetify</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons">
	<link rel="stylesheet" type="text/css" href="https://unpkg.com/vuetify/dist/vuetify.min.css">
	<link rel="stylesheet" type="text/css" href="/assets/css/site.css">
</head>
<body>
<div id="app">

	<v-app>
		<v-content>
			<v-container>
				<template v-if="isActive">
					<v-list-tile v-for="(item, index) in registration" :key="index">
		              <v-list-tile-content>
		                <v-list-tile-title>{{ index }}</v-list-tile-title>
		                <v-list-tile-sub-title>Answer: {{ item }}</v-list-tile-sub-title>
		              </v-list-tile-content>
		            </v-list-tile>
					<br>
					<br>
				</template>
				<v-stepper v-model="step" vertical>
					<v-stepper-header>
						<v-stepper-step
							v-for="(question, index) in questions"
							:key="index"
							:step="question.sort_order"
							:complete="step > question.sort_order"
						>
							<span class="font-sr">{{ question.titleKh }}</span>
						</v-stepper-step>
					</v-stepper-header>

					<v-stepper-items>
					<?php foreach($questions as $key => $question): ?>
						<v-stepper-content
							step="<?php echo $question->sort_order; ?>"
						>
<?php if($question->type == 1): ?>
	<v-radio-group v-model="registration.question<?php echo $question->sort_order; ?>">
	<?php foreach($question->answers as $key => $answer): ?>
		<v-radio
		label="<?php echo $answer->title; ?>" class="font-sr"
		value="<?php echo $answer->title; ?>"
		></v-radio>
	<?php endforeach ?>
	</v-radio-group>
<?php else: ?>

<?php foreach($question->answers as $key => $answer): ?>
<v-checkbox 
	v-model="registration.question<?php echo $question->sort_order; ?>"
	label="<?php echo $answer->title; ?>" class="font-sr"
	value="<?php echo $answer->title; ?>"
	>
</v-checkbox>
<?php endforeach ?>
					<?php endif ?>

<?php if($question->sort_order > 1):  ?>
	<v-btn flat @click.native="step = <?php echo ($question->sort_order - 1); ?>">Previous</v-btn>
<?php endif ?>

<?php if($question->sort_order !== $questions->count()): ?>
	<v-btn color="primary" 
		@click.native="step = <?php echo ($question->sort_order + 1); ?>"
		:disabled="registration.question<?php echo $question->sort_order; ?> == null">
		Continue
	</v-btn>
<?php endif ?>

<?php if($question->sort_order === $questions->count()): ?>
	<v-btn color="primary" 
		@click.prevent="submit"
		:disabled="registration.question<?php echo $question->sort_order; ?> == null">
		Complete
	</v-btn>
<?php endif ?>
						</v-stepper-content>
					<?php endforeach ?>
					</v-stepper-items>
				</v-stepper>
			</v-container>    
		</v-content>
	</v-app>
</div>


	<script src="https://unpkg.com/vue/dist/vue.js"></script>
	<script src="https://unpkg.com/vuetify/dist/vuetify.min.js"></script>
	<script>
		new Vue({
		  el: '#app',
		  data: () => ({
		      step:1,
		      isActive: false,
		      questions: [],
		      registration: {
		        question1: null,
		        question2: null,
		        question3: [],
		        question4: null,
		        question5: [],
		      }
		  }),
		  methods:{
		    submit() {
		    	this.isActive = true
		    }
		  },
		  mounted(){
		  	this.questions = <?php echo $questions->toJson() ?>
		  }
		})
	</script>
</body>
</html>