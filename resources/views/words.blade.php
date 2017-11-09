@extends('layouts.app')

@section('content')
        <div>
            <table border="1px">
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
                @foreach ($wordCollection as $wordid=>$wordhash)
                    <tr data-word="{{$vocabulary[$wordid]}}">
                        <td>{{$vocabulary[$wordid]}}</td>
                        <td data-algoritm="md5">
                            @if(isset($wordhash['md5']))
                                <div>{{$wordhash['md5']}}</div>
                            @else
                                <button>Convert to MD5</button>
                            @endif
                        </td>
                        <td data-algoritm="sha1">
                            @if(isset($wordhash['sha1']))
                                <div>{{$wordhash['sha1']}}</div>
                            @else
                                <button>Convert to sha1</button>
                            @endif
                        </td>
                        <td data-algoritm="crc32">
                            @if(isset($wordhash['crc32']))
                                <div>{{$wordhash['crc32']}}</div>
                            @else
                                <button>Convert to crc32</button>
                            @endif
                        </td>
                        <td data-algoritm="SHA256">
                            @if(isset($wordhash['SHA256']))
                                <div>{{$wordhash['SHA256']}}</div>
                            @else
                                <button>Convert to SHA256</button>
                            @endif
                        </td>
                        <td data-algoritm="base64">
                            @if(isset($wordhash['base64']))
                                <div>{{$wordhash['base64']}}</div>
                            @else
                                <button>Convert to base64</button>
                            @endif
                        </td>
                        <td data-algoritm="delete">
                           <button>Delete hash for a word</button>
                        </td>
                    </tr>

          
                @endforeach
             </table>
             <button>Add word</button>
        </div> 
@endsection
