<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EncoderController extends Controller
{
    public function convertToSHA1($word){
    	return sha1($word);
    }

    public function convertToMD5($word){
    	return md5($word);
    }

    public function convertToCRC32($word){
    	return crc32($word);
    }

    public function convertToSHA256($word){
    	return hash('sha256', $word);
    }
}
