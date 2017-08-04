<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  protected $table = 'categories';

  protected $fillable = [
     'name'
  ];

  public function News(){
    return $this->hasMany('App\News','category_id');
  }
}
