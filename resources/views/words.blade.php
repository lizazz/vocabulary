@extends('layouts.app')
<script src="https://unpkg.com/vue"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
@section('content')
        <div>
            <table border="1px" id="app3">
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
                        <td v-if="wordhash['md5'] == undefined">
                            <button v-on:click="convert(wordid,'md5')">Convert to MD5</button>
                        </td>
                        <td v-else>
                                <div>@{{wordhash['md5']}}</div>
                        </td>
                        <td v-if="wordhash['sha1'] == undefined">
                            <button v-on:click="convert(wordid,'sha1')">Convert to sha1</button>
                        </td>
                        <td v-else>
                                <div>@{{wordhash['sha1']}}</div>
                        </td>
                        <td v-if="wordhash['crc32'] == undefined">
                            <button v-on:click="convert(wordid,'crc32')">Convert to crc32</button>
                        </td>
                        <td v-else>
                                <div>@{{wordhash['crc32']}}</div>
                        </td>
                        <td v-if="wordhash['SHA256'] == undefined">
                            <button v-on:click="convert(wordid,'SHA256')">Convert to SHA256</button>
                        </td>
                        <td v-else>
                                <div>@{{wordhash['SHA256']}}</div>
                        </td>
                        <td v-if="wordhash['base64'] == undefined">
                            <button v-on:click="convert(wordid,'base64')">Convert to base64</button>
                        </td>
                        <td v-else>
                                <div>@{{wordhash['base64']}}</div>
                        </td>
                        <td data-algoritm="delete">
                           <button>Delete hash for a word</button>
                        </td>
                    </tr>
             </table>
            <div id='addrow'>
                <button v-on:click="addRow('Add word')">Add word</button>
            </div>
        </div>  
<div id="app2">
<example></example>
</div>
<script type="text/javascript" src="/resources/assets/js/main.js">
</script>
@endsection
