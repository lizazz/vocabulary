const app3 = new Vue({
    el: '#app3',
    mounted() {
    	axios.get('/gethashword').then(response => [this.message = response.data, this.vocabulary = response.data['vocabulary'], this.wordCollection = response.data['wordCollection']]);
    },
      data: {
      	message:'',
      	vocabulary:[],
      	wordCollection:[],
      	selected: ''
      },
      methods:{
         showhash: function(wordid,algoritm){
          if(this.wordCollection[wordid][algoritm] == undefined){
            var content = '<button v-on:click="convert(' + wordid + ',\'' + algoritm + '\')">Convert to ' + algoritm + '</button>'
          }else{
            var content = this.wordCollection[wordid][algoritm];
          }
          return content;
        },
      	convert: function(wordid, algoritm){
           var vm = this;
    		    axios({
    			  method: 'get',
    			  url: '/encode?wordid=' + wordid + '&algoritm=' + algoritm,
    			  data: {
    			    wordid: wordid,
    			    algoritm: algoritm,
    			  }
    			})
    		    .then(function (response) {
              if(response['data'] != false){
                console.log('before = ' + vm.wordCollection[response['data']['wordid']][response['data']['algoritm']]);
                vm.wordCollection[response['data']['wordid']][response['data']['algoritm']] = response['data']['wordhash'];
              }

    		    })

    		}
      }
});

var addrow = new Vue({
	el:'#addrow',
	  mounted() {
    	axios.get('/nouserwords').then(response => this.wordCollection = response.data);
    },
    data:{
    	wordid:0
    },
	methods:{
      	addRow: function(event){
      		if(event){
      			var select = '<select class="newword">';
      			select += '<option value="0" disabled selected>Select word</option>';
      			for(var i in this.wordCollection){
      				select += '<option value = "' + i + '">' + this.wordCollection[i] + '</option>';
      			}
      			select += '</select>';	
      			var table = document.getElementById('app3');
      			var tr = document.createElement('tr');
      			var td = document.createElement('td');
      			td.innerHTML = select;
      			tr.appendChild(td);
      			var td2 = document.createElement('td');
      			var td3 = document.createElement('td');
      			var td4 = document.createElement('td');
      			var td5 = document.createElement('td');
      			var td6 = document.createElement('td');
      			var td7 = document.createElement('td');
      			td2.innerHTML = '<button onclick="convertNewLine(this,\'md5\')">Convert to MD5</button>';
      			td3.innerHTML = '<button onclick="convertNewLine(this,\'sha1\')">Convert to sha1</button>';
      			td4.innerHTML = '<button onclick="convertNewLine(this,\'crc32\')">Convert to crc32</button>';
      			td5.innerHTML = '<button onclick="convertNewLine(this,\'sha256\')">Convert to SHA256</button>';
      			td6.innerHTML = '<button onclick="convertNewLine(this,\'base64\')">Convert to base64</button>';
      			td7.innerHTML = '<button>Delete hash for a word</button>';
      			tr.appendChild(td);
      			tr.appendChild(td2);
      			tr.appendChild(td3);
      			tr.appendChild(td4);
      			tr.appendChild(td5);
      			tr.appendChild(td6);
      			tr.appendChild(td7);
      			table.appendChild(tr);
      		}
      		
      	}
      },
     data: {
      	wordCollection:[]
      }

});

/*new Vue({
  el: '#sel1',
  data: {
    selected: ''
  }
})*/

/*var app5 = new Vue({
	el: '#app5',
	data: {
		'message' : 'hello'
	},
	methods: {

	}
});*/

function convertNewLine(buttonObject, algoritm){
	var tr = buttonObject.parentElement.parentElement;
	var select = tr.getElementsByTagName('select');
	var wordid = select[0].value;
	/*if(wordid > 0){
		axios({
	  method: 'get',
	  url: '/encode?wordid=' + wordid + '&algoritm=' + algoritm,
	  data: {
	    wordid: wordid,
	    algoritm: algoritm
	  }
	})
	.then(function (response) {
	  console.log(response['data']);
	})	
	}*/
	/**/
}