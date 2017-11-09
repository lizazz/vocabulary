<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{
    protected $table = 'vocabulary';
    protected $primaryKey = 'id';
    protected $fillable = ['word'];
    public $timestamps = false;
}
