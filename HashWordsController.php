<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserExtraInformation;
use App\HashTable;
use App\Vocabulary;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EncoderController;

class HashWordsController extends Controller
{
    /**
     * Display all words and hash for selected.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::id();
        $hashwords = HashTable::where('userid', $id)->get();
        $wordCollection = [];
        $words = [];
        foreach ($hashwords as $word) {
            $wordCollection[$word->wordid][$word->algoritm] = $word->wordhash;
            $words[] = $word->wordid;
        }
        $words = array_unique($words);
        $words = Vocabulary::All();
        $vocabulary = [];
        foreach ($words as $word) {
            $vocabulary[$word->id]= $word->word;
        }
        return view('words', ['vocabulary' => $vocabulary, 'wordCollection' => $wordCollection]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function encode(){
        if(!isset($_GET) || !isset($_GET['wordid']) || !isset($_GET['algoritm']) || $_GET['wordid'] <= 0 || $_GET['algoritm'] == ''){
           return 'false';
        }
        $userid = Auth::id();

        $hashword = HashTable::where('userid', $userid)->where('wordid', $_GET['wordid'])->where('algoritm', $_GET['algoritm'])->first();
        if(isset($hashword['id'])){
            return 'false';
        }
        $word = Vocabulary::where('id', $_GET['wordid'])->first();
        if(!isset($word['word'])){
            return 'false';
        }
        $encoderObject = new EncoderController();
        $encodeString = '';
        $_GET['algoritm'] = 'sha256';
        switch ($_GET['algoritm']) {
            case 'sha1':
               $encodeString = $encoderObject->convertToSHA1($word['word']);
                break;
            case 'md5':
               $encodeString = $encoderObject->convertToMD5($word['word']);
                break;
            case 'crc32':
               $encodeString = $encoderObject->convertToCRC32($word['word']);
                break;
            case 'sha256':
               $encodeString = $encoderObject->convertToSHA256($word['word']);
                break;
            default:
                # code...
                break;
        }

        return $encodeString;
    }
}
