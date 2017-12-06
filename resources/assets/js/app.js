
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
/*
Vue.component('ExampleComponent', require('./components/ExampleComponent.vue'));
*/

/*const app = new Vue({
    el: '#app'
});*/

// Шаги алгоритма ECMA-262, 5-е издание, 15.4.4.18
// Ссылка (en): http://es5.github.io/#x15.4.4.18
// Ссылка (ru): http://es5.javascript.ru/x15.4.html#x15.4.4.18
//window.Vue = require('vue');

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

var algoritms = ['md5', 'sha1', 'crc32', 'sha256', 'base64'];
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
        addRow: function(event){
          if(event){
            var table = document.getElementById('maintable');
            var tr = document.createElement('tr');
            var tds = '<td><select class="newword">';
            tds += '<option value="0" disabled selected>Select word</option>';
            for(var i in this.vocabulary){
            	var disabled = '';
            	if(this.wordCollection[i] != undefined){
            		disabled = 'disabled';
            	}
              tds += '<option value = "' + i + '" ' + disabled + ' >' + this.vocabulary[i] + '</option>';
            }
            tds += '</select>';
            tds += '</td>';
            algoritms.forEach(function callback(currentValue, index, array) {
                //vm.wordCollection[wordid][currentValue] = '';
                tds += '<td><button onclick="convertNewLine(this,\'' + currentValue + '\')">Convert to ' + currentValue + '</button></td>';
            });
            tds += '<td><button onclick="deleteNewLine(this)">Delete hash for a word</button></td>';
            tr.innerHTML = tds;
            table.appendChild(tr);
          }
        },

      }
});


/*function convertNewLine(buttonObject, algoritm){
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
	      select[0].disabled = true;
	  	});
	}else{
		alert('Select word, please');
	}
}

function deleteNewLine(buttonObject){
  var tr = buttonObject.parentElement.parentElement;
  var select = tr.getElementsByTagName('select');
  var wordid = select[0].value;
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
*/
// Edit word section
const app4 = new Vue({
    el: '#app4',
    mounted() {
      axios.get('/getwords').then(response => [this.message = response.data, this.vocabulary = response.data['vocabulary']]);
    },
      data: {
        message:'',
        vocabulary:[]
      },
      methods:{
        deleteword: function(wordid){
          if(wordid > 0){
            var decision = confirm('Are you sure? The word and all hashes for this word will be deleted');
            if(decision){
              var vm = this;
              axios({
                method: 'get',
                url: '/deleteword?wordid=' + wordid,
                data: {
                  wordid: wordid,
                }
              })
              vm.vocabulary[wordid] = null;
            /*  algoritms.forEach(function callback(currentValue, index, array) {
                vm.wordCollection[wordid][currentValue] = '';
              });*/
            }
          }else{
            alert('Select word, please');
          }
        },
        addInputRow: function(){
            var table = document.getElementById('maintable');
            var tr = document.createElement('tr');
            var tds = '<td>';
            tds += '<input type="text" name="newword" placeholder="new word">';
            tds += '</td>';
            tds += '<td><button onclick="addNewword(this)">Save word</button></td>';
            tr.innerHTML = tds;
            table.appendChild(tr);
        }
      }
});

/*function addNewword(buttonObject) {
  var tr = buttonObject.parentElement.parentElement;
  var input = tr.getElementsByTagName('input');
  var word = input[0].value;
  if(word.length > 0){
      axios({
        method: 'get',
        url: '/saveword?word=' + word,
        data: {
          wordid: word
        }
      })
      .then(function (response) {
        var td = buttonObject.parentElement.innerHTML = '<button onclick="deleteNewWord(this, ' + response['data']['id'] + ')">Delete a word</button>';
        input[0].disabled = true;
      });
  }
}

function deleteNewWord(buttonObject, wordid){
  var tr = buttonObject.parentElement.parentElement;
  if(wordid > 0){
    var decision = confirm('Are you sure?');
    if(decision){
      axios({
      method: 'get',
        url: '/deleteword?wordid=' + wordid,
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


*/