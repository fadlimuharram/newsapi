<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Public_profile extends Model
{
    protected $table = 'public_profiles';

    protected $fillable = [
       'name','user_id'
    ];

    

    public $timestamps = false;

    public function user(){
      return $this->belongsTo('App\User','user_id');
    }
}
