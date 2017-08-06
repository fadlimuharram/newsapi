<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChildComments extends Model
{
  protected $table = 'childcomments';

  protected $fillable = [
     'email','name','comment','ip_address','comments_id'
  ];

  public function Comment(){
    return $this->belongsTo('App\ChildComments','comments_id');
  }
}
