<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserExtraInformation;
use App\HashTable;
use App\Vocabulary;
use App\Http\Controllers\HashWordsController;

class XMLReport extends Controller
{
    /**
     * form user data for export to xml
     * @return array user data
     */
    public function getXML(){

    	$users = User::All();
    	
    	$userData = [];
    	foreach ($users as $user) {
    		$vocabulary = $this->formVocabulary($user['id']);
            $userData[$user['id']]['email'] = $user['email'];
    		$userData[$user['id']]['vocabulary'] = $vocabulary;
            $userExtraInformation = UserExtraInformation::where('userid', $user['id'])->first();
            $userData[$user['id']]['ipaddress'] = $userExtraInformation->ipaddress;
            $userData[$user['id']]['browser'] = $userExtraInformation->browser;
            $userData[$user['id']]['country'] = $userExtraInformation->country;
            $userData[$user['id']]['cookie'] = $userExtraInformation->cookie;
    	}

    	return $userData;
    }

    /**
     * form words and their hashes for export to xml
     * @param  int $userid
     * @return array words and hashes
     */
    public function formVocabulary($userid){
        $vocabulary = [];
        $HashWordsObject = new HashWordsController();
        $hashwords = $HashWordsObject->show($userid);
        $vocabulary = $hashwords['vocabulary'];
        foreach ($hashwords['vocabulary'] as $wordid => $word) {
            $vocabulary[$wordid] = ['original' => $word, 'id' => $wordid];
            if(isset($hashwords['wordCollection'][$wordid])){
                $vocabulary[$wordid]['hashes'] = $hashwords['wordCollection'][$wordid];
            }else{
                $vocabulary[$wordid]['hashes'] = ['crc32' => '', 'md5' => '', "sha1" => '', "sha256" => '', 'base64' =>''];
            }
        }
        return $vocabulary;
    }
}
