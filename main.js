var app5 = new Vue({
	el: '#app5',
	data: {
		'message' : 'hello'
	},
	methods: {
		convert: function(wordid, algoritm){
			axios({
			  method: 'get',
			  url: '/encode?wordid=' + wordid + '&algoritm=' + algoritm,
			  data: {
			    wordid: wordid,
			    algoritm: algoritm
			  }
			})
		    .then(function (response) {
		    	console.log(this);
		      console.log(response['data']);
		    })
		}
	}
});