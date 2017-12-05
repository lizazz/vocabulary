<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserExtraInformation;
use App\HashTable;
use App\Vocabulary;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EncoderController;
use Illuminate\Http\Response;

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
    public function show()
    {
        $userid = Auth::id();
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

    public function hashjson(){
      //  var_dump($_POST);
        echo json_encode($request);
        return json_encode($_POST);
    }

    public function test(){
        $_dwkUri = "http://vocabulary/hashjson";
        $ch = curl_init($_dwkUri);
        $_postData = ['login' => 'vpetrechenko@holbi.co.uk', 'password' => '123456', '_token' => csrf_token()];
        $_postData = json_encode($_postData);
        curl_setopt($ch, CURLOPT_URL, $_dwkUri); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json')); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_postData); 
        $result = curl_exec($ch); 
        curl_close($ch); 
        echo $result;
       /* $_postData = ['login' => 'vpetrechenko@holbi.co.uk', 'password' => '123456'];
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);        
    //  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;
    //  curl_setopt($ch, CURLOPT_USERPWD, "vpetrechenko@holbi.co.uk:123456");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));  
        curl_setopt($ch, CURLOPT_POST, false);
        $_postData = json_encode($_postData);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_postData);       
        $curlResults = curl_exec($ch);
        
        if (curl_errno($ch))
        {
            //throw new Exception("There was an error: " . curl_errno($ch) . " " . curl_error($ch));
        
            return false;
        }
        
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $curlResults = json_decode($curlResults);
        echo "<pre>";
        print_r($curlResults);
        echo "</pre>";
      /*  echo $http_status . "<br>";
        if(!in_array($http_status, array(200, 201, 204)))
        {
        //  throw new Exception($curlResults);
            
            return false;
        }
        
        curl_close($ch);
        
        if(!$_overrideUrl)
        {
            $curlResults = json_decode($curlResults);
        }
        echo $curlResults;
        return $curlResults;*/
    }
}
