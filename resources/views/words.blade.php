@extends('layouts.app')
<!--<script src="https://unpkg.com/vue"></script>-->
@section('content')
<div id="app4" display="none"></div>
        <div>
            <a href="{{ url('/editword') }}">
                Edit words
            </a>
        </div>
        <br/>
        <div id="app3">
            <div id='addrow'>
                <button v-on:click="addRow('Add word')">Add word for hashing</button>
            </div>
            <table border="1px" id="maintable">
                <thead>
                    <tr>
                        <td>word</td>
                        <td>md5</td>
                        <td>sha1</td>
                        <td>crc32</td>
                        <td>SHA256</td>
                        <td>base64</td>
                        <td>Delete</td>
                    </tr>
                </thead>
                <tr v-for="(wordhash, wordid ) in wordCollection">
                        <td> @{{vocabulary[wordid]}}</td>
                       <td v-if="wordhash['md5'].length == 0">
                            <button v-on:click="convert(wordid,'md5')">Convert to MD5</button>
                        </td>
                        <td v-else>
                                <div>@{{wordhash['md5']}}</div>
                        <td v-if="wordhash['sha1'].length == 0">
                            <button v-on:click="convert(wordid,'sha1')">Convert to sha1</button>
                        </td>
                        <td v-else>
                                <div>@{{wordhash['sha1']}}</div>
                        </td>
                        <td v-if="wordhash['crc32'].length == 0">
                            <button v-on:click="convert(wordid,'crc32')">Convert to crc32</button>
                        </td>
                        <td v-else>
                                <div>@{{wordhash['crc32']}}</div>
                        </td>
                        <td v-if="wordhash['sha256'].length == 0">
                            <button v-on:click="convert(wordid,'sha256')">Convert to SHA256</button>
                        </td>
                        <td v-else>
                                <div>@{{wordhash['sha256']}}</div>
                        </td>
                        <td v-if="wordhash['base64'].length == 0">
                            <button v-on:click="convert(wordid,'base64')">Convert to base64</button>
                        </td>
                        <td v-else>
                                <div>@{{wordhash['base64']}}</div>
                        </td>
                        <td data-algoritm="delete">
                           <button v-on:click="deleteHash(wordid)">Delete hash for a word</button>
                        </td>
                    </tr>
             </table>
        </div>
<!--<script type="text/javascript" src="/resources/assets/js/main.js">
</script> -->
@endsection
