<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
  protected $table = 'admins';

  protected $fillable = [
     'name','user_id'
  ];

  

  public $timestamps = false;

  public function user(){
    return $this->belongsTo('App\User','user_id');
  }
}
