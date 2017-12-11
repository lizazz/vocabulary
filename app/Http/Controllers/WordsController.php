<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vocabulary;
use Illuminate\Support\Facades\Auth;

class WordsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('editwords');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!isset($_GET) || !isset($_GET['word']) || $_GET['word'] == ''){
           return 'false';
        }
        $newWord = ['word' => $_GET['word']];
        $wordObject =Vocabulary::create($newWord);
        return $wordObject;
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $words = Vocabulary::All();
        $vocabulary = [];
        foreach ($words as $word) {
            $vocabulary[$word->id]= $word->word;
        }

        return ['vocabulary' => $vocabulary];
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        if(!isset($_GET) || !isset($_GET['wordid']) || $_GET['wordid'] <= 0){
           return 'false';
        }
        $userid = Auth::id();
        $hashword = Vocabulary::where('id', $_GET['wordid'])->delete();
        return 'true';
    }
}
