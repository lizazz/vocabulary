<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserExtraInformation;
use GeoIP;

class UserExtraInformationController extends Controller
{
    /**
     * Save extra information of user
     * @param  int $userid
     * @param  array $data from request
     */
    function saveExtraInformation($userid, $data){
    	//$country = $this->strana( '91.193.173.11');
    	$GeoIPObject = GeoIP::getLocation($data['ipaddress']/*'91.193.173.11'*/);
        if(isset($GeoIPObject->country) && strlen($GeoIPObject->country) > 0){
        	$country = $GeoIPObject->country;
        }else{
        	$country = '';
        }
;
    	UserExtraInformation::create([
            'userid' => $userid,
            'ipaddress' => $data['ipaddress'],
            'browser'   => $data['browser'],
            'country' => $country
        ]);
    }
}
