// Шаги алгоритма ECMA-262, 5-е издание, 15.4.4.18
// Ссылка (en): http://es5.github.io/#x15.4.4.18
// Ссылка (ru): http://es5.javascript.ru/x15.4.html#x15.4.4.18
if (!Array.prototype.forEach) {

  Array.prototype.forEach = function (callback, thisArg) {

    var T, k;

    if (this == null) {
      throw new TypeError(' this is null or not defined');
    }

    // 1. Положим O равным результату вызова ToObject passing the |this| value as the argument.
    var O = Object(this);

    // 2. Положим lenValue равным результату вызова внутреннего метода Get объекта O с аргументом "length".
    // 3. Положим len равным ToUint32(lenValue).
    var len = O.length >>> 0;

    // 4. Если IsCallable(callback) равен false, выкинем исключение TypeError.
    // Смотрите: http://es5.github.com/#x9.11
    if (typeof callback !== 'function') {
        throw new TypeError(callback + ' is not a function');
    }

    // 5. Если thisArg присутствует, положим T равным thisArg; иначе положим T равным undefined.
    if (arguments.length > 1) {
      T = thisArg;
    }

    // 6. Положим k равным 0
    k = 0;

    // 7. Пока k < len, будем повторять
    while (k < len) {

      var kValue;

      // a. Положим Pk равным ToString(k).
      //   Это неявное преобразование для левостороннего операнда в операторе in
      // b. Положим kPresent равным результату вызова внутреннего метода HasProperty объекта O с аргументом Pk.
      //   Этот шаг может быть объединён с шагом c
      // c. Если kPresent равен true, то
      if (k in O) {

        // i. Положим kValue равным результату вызова внутреннего метода Get объекта O с аргументом Pk.
        kValue = O[k];

        // ii. Вызовем внутренний метод Call функции callback с объектом T в качестве значения this и
        // списком аргументов, содержащим kValue, k и O.
        callback.call(T, kValue, k, O);
      }
      // d. Увеличим k на 1.
      k++;
    }
    // 8. Вернём undefined.
  };
}

var algoritms = ['md5', 'sha1', 'crs32', 'sha256', 'base64'];
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
                vm.wordCollection[response['data']['wordid']][response['data']['algoritm']] = response['data']['wordhash'];
              }

    		    })

    		},
        deleteHash: function(wordid){
          if(wordid > 0){
            var decision = confirm('Are you sure? All hashes for this word will be deleted');
            if(decision){
              var vm = this;
             // vm.wordCollection[wordid]['sha256'] = '';
            // console.log(vm.wordCollection[wordid]);
              axios({
                method: 'get',
                url: '/deletehash?wordid=' + wordid,
                data: {
                  wordid: wordid,
                }
              })
                algoritms.forEach(function callback(currentValue, index, array) {
                vm.wordCollection[wordid][currentValue] = '';
            });
            }
          }else{
            alert('Select word, please');
          }
        },
        
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
      			td7.innerHTML = '<button onclick="deleteNewLine(this)">Delete hash for a word</button>';
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


function convertNewLine(buttonObject, algoritm){
	var tr = buttonObject.parentElement.parentElement;
	var select = tr.getElementsByTagName('select');
	var wordid = select[0].value;
	if(wordid > 0){
		axios({
	  method: 'get',
  	  url: '/encode?wordid=' + wordid + '&algoritm=' + algoritm,
  	  data: {
  	    wordid: wordid,
  	    algoritm: algoritm
  	  }
  	})
  	.then(function (response) {
      var td = buttonObject.parentElement.innerHTML = response['data']['wordhash'];
     // console.log(buttonObject);
  	})	
	}else{
    alert('Select word, please');
  }
}

function deleteNewLine(buttonObject){
  var tr = buttonObject.parentElement.parentElement;
  var select = tr.getElementsByTagName('select');
  var wordid = select[0].value;
  console.log(wordid);
  if(wordid > 0){
    var decision = confirm('Are you sure? All hashes for this word will be deleted');
    if(decision){
      axios({
      method: 'get',
        url: '/deletehash?wordid=' + wordid,
        data: {
          wordid: wordid
        }
      });
      tr.remove();
    }
  }else{
    alert('Select word, please');
  }
}