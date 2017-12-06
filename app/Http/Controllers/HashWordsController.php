<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserExtraInformation;
use App\HashTable;
use App\Vocabulary;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EncoderController;
use Illuminate\Http\Response;
use App\User;

class HashWordsController extends Controller
{
    /**
     * Display all words and hash for selected.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('words');
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($userid = 0)
    {
        if($userid == 0){
            $userid = Auth::id();
        }
        $hashwords = HashTable::where('userid', $userid)->get();
        $wordCollection = [];
        $words = [];
        foreach ($hashwords as $word) {
            $wordCollection[$word->wordid][$word->algoritm] = $word->wordhash;
            $words[] = $word->wordid;
        }
        foreach ($wordCollection as $wordid => $collection) {
            if(!isset($wordCollection[$wordid]['md5'])){
                $wordCollection[$wordid]['md5'] = '';
            }
            if(!isset($wordCollection[$wordid]['sha1'])){
                $wordCollection[$wordid]['sha1'] = '';
            }
            if(!isset($wordCollection[$wordid]['crc32'])){
                $wordCollection[$wordid]['crc32'] = '';
            }
            if(!isset($wordCollection[$wordid]['sha256'])){
                $wordCollection[$wordid]['sha256'] = '';
            }
            if(!isset($wordCollection[$wordid]['base64'])){
                $wordCollection[$wordid]['base64'] = '';
            }
        }
        $words = array_unique($words);
        $words = Vocabulary::All();
        $vocabulary = [];
        foreach ($words as $word) {
            $vocabulary[$word->id]= $word->word;
        }

        return ['vocabulary' => $vocabulary, 'wordCollection' => $wordCollection];
    }

    public function nouserwords(){
        $userid = Auth::id();
        $hashwords = HashTable::where('userid', $userid)->get();
        $wordCollection = [];
        foreach ($hashwords as $word) {
            $wordCollection[] = $word->wordid;
        }
        $wordCollection = array_unique($wordCollection);
     //   print_r($wordCollection);
        $words = Vocabulary::whereNotIn('id',$wordCollection)->get();
        $vocabulary = [];
        foreach ($words as $word) {
            $vocabulary[$word->id]= $word->word;
        }
        return $vocabulary;
    }

    public function encode(){
        if(!isset($_GET) || !isset($_GET['wordid']) || !isset($_GET['algoritm']) || $_GET['wordid'] <= 0 || $_GET['algoritm'] == ''){
           return 'false';
        }
        $userid = Auth::id();

        $hashword = HashTable::where('userid', $userid)->where('wordid', $_GET['wordid'])->where('algoritm', $_GET['algoritm'])->first();
        if(isset($hashword['id'])){
            //return 'false';
            $newRow = ['userid' => $userid, 'wordid' => $_GET['wordid'], 'algoritm' => $_GET['algoritm'], 'wordhash' => $hashword['wordhash']];
            return $newRow;
        }
        $word = Vocabulary::where('id', $_GET['wordid'])->first();
        if(!isset($word['word'])){
            return 'false';
        }
        $encoderObject = new EncoderController();
        $encodeString = '';
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
            case 'base64':
               $encodeString = $encoderObject->convertToBase64($word['word']);
                break;
            default:
                # code...
                break;
        }

        if($encodeString != ''){
            $newRow = ['userid' => $userid, 'wordid' => $_GET['wordid'], 'algoritm' => $_GET['algoritm'], 'wordhash' => $encodeString];
            HashTable::create($newRow);
            return $newRow;
        }
        return 'false';
    }

    public function deleteHash(){
        if(!isset($_GET) || !isset($_GET['wordid']) || $_GET['wordid'] <= 0){
           return 'false';
        }
        $userid = Auth::id();
        $hashword = HashTable::where('userid', $userid)->where('wordid', $_GET['wordid'])->delete();
        return 'true';
    }

    public function hashjson(Request $request){
        $login = $request->input('login');
        $pass = $request->input('password');
        $responce = [];
         if(!$login || !$pass){
            $responce = ['error' => true, 'message' => 'wrong auth data'];
            return json_encode($responce);
         }
         $answer = Auth::attempt(['email' => $login, 'password' => $pass]);
         if(!$answer){
            $responce = ['error' => true, 'message' => 'wrong auth data'];
            return json_encode($responce);
         }
         $user_id = User::select('id')->where('email', $login)->first();
         $hashesRow = $this->show($user_id['id']);
         if(!is_array($hashesRow)){
            $responce = ['error' => true, 'message' => 'problem with DB connection'];
            return json_encode($responce);
         }
         $hashes = [];
         foreach ($hashesRow['wordCollection'] as $wordid => $hash) {
             if(isset($hashesRow['vocabulary'][$wordid])){
                foreach ($hash as $algoritm => $value) {
                    if(strlen($value) > 0){
                        $hashes[$hashesRow['vocabulary'][$wordid]][$algoritm] = $value;
                    }
                }
             }
         }
         $responce = $hashes;
        return json_encode($responce);
    }

    public function test(){
        $_dwkUri = "http://vocabulary/hashjson";
        $ch = curl_init($_dwkUri);
        $_postData = ['_token' => csrf_token(), 'login' => 'eaklimka@test.com', 'password' => '123456'];
        $_postData = json_encode($_postData);
        curl_setopt($ch, CURLOPT_URL, $_dwkUri); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json')); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_postData); 
        $result = curl_exec($ch); 
        curl_close($ch); 
        echo $result;
    }
}
