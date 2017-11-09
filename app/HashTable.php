<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HashTable extends Model
{
    protected $table = 'hashtable';
    protected $primaryKey = 'id';
    protected $fillable = ['userid', 'wordid', 'algoritm', 'wordhash'];
    public $timestamps = false;
}
