<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserExtraInformation extends Model
{
    protected $table = 'userextrainformation';
    protected $primaryKey = 'id';
    protected $fillable = ['userid', 'ipaddress', 'browser', 'country', 'cookie'];
    public $timestamps = false;
}
