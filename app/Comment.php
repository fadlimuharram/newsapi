<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
  protected $table = 'comments';

  protected $fillable = [
     'email','name','comment','ip_address','news_id'
  ];

  public function ChildComments(){
    return $this->hasMany('App\ChildComments','comments_id');
  }

}
