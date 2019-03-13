new Vue({
  el: '#app',
  data: {
    imgList: [
    	'http://via.placeholder.com/350x150',
      'http://via.placeholder.com/350x151',
      'http://via.placeholder.com/350x152'
    ],
    currentImg: 0
  },
  mounted() {
  	setInterval(() => {
    	this.currentImg = this.currentImg + 1;
    }, 3000);
  }
})