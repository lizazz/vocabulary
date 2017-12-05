@extends('layouts.app')
<script src="https://unpkg.com/vue"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
@section('content')
<div id="app3" display="none"></div>
        <div>
            <a href="{{ url('/hashwords') }}">
                Hash Words
            </a>
        </div>
        <br>
        {{ csrf_field() }}
        <div id="app4">
            <div id='addrow'>
                <button v-on:click="addInputRow()">New word</button>
            </div>
            <table border="1px" id="maintable">
                <thead>
                    <tr>
                        <td>word</td>
                        <td>Delete</td>
                    </tr>
                </thead>
                <tr v-for="(word, wordid ) in vocabulary">
                    <td v-if="word != null">@{{word}}</td>
                    <td v-if="word != null">
                           <button v-on:click="deleteword(wordid)">Delete a word</button>
                    </td>
                </tr>
            </table>
        </div>

<script type="text/javascript" src="/resources/assets/js/main.js">
</script>
@endsection
