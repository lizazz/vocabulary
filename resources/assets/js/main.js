const app3 = new Vue({
    el: '#app3',
    mounted() {
    	axios.get('/gethashword').then(response => [this.message = response.data, this.vocabulary = response.data['vocabulary'], this.wordCollection = response.data['wordCollection']]);
    },
      data: {
      	message:'',
      	vocabulary:[],
      	wordCollection:[]
      },
      methods:{
      	convert: function(wordid, algoritm){
      		console.log(wordid + ' ' + algoritm);
      	}
      }
      
});

var addrow = new Vue({
	el:'#addrow',
	  mounted() {
    	axios.get('/nouserwords').then(response => this.wordCollection = response.data);
    },
	methods:{
      	addRow: function(event){
      		if(event){
      			var select = "<select>";
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
      			td2.innerHTML = '<button>Convert to MD5</button>';
      			td3.innerHTML = '<button>Convert to sha1</button>';
      			td4.innerHTML = '<button>Convert to crc32</button>';
      			td5.innerHTML = '<button>Convert to SHA256</button>';
      			td6.innerHTML = '<button>Convert to base64</button>';
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