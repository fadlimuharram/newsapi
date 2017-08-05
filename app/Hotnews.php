<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hotnews extends Model
{
  protected $table = 'hotnews';

  protected $fillable = [
     'news_id','created_at'
  ];

  public $timestamps = false;

  public function News(){
    return $this->belongsTo("App\News","news_id");
  }
}
