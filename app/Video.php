<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
  protected $table = 'videos';

  protected $fillable = [
     'namevid','poster','title','description','byadmin'
  ];

  protected $hidden = ['byadmin'];
}
