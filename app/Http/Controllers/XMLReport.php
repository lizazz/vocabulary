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
    public function getXML(){

    	$users = User::All();
    	$words = Vocabulary::All();
    	//$hashes = HashTable::All();
    	$userData = [];
    	$HashWordsObject = new HashWordsController();
    	foreach ($users as $user) {
    		$vocabulary = $this->formVocabulary($user['id']);
    		$userData[$user['email']] = $vocabulary;
    	}
    	dump($userData);
    	return $userData;
    }

    public function formVocabulary($userid){
    	$rowArray = $HashWordsObject->show($user['id']);
    	$words = [];
    	if(is_array($rowArray['wordCollection']) && count($rowArray['wordCollection']) > 0){
    		foreach ($variable as $key => $value) {
    			# code...
    		}
    	}
    }
}
