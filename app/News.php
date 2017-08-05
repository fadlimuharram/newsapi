<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
       'title','hit','enablecomment','short_content','content','cover','byadmin','category_id'
    ];

    protected $hidden = [
      'byadmin'
    ];

    public function Category(){
      return $this->belongsTo('App\Category','category_id');
    }

    public function Hotnews(){
      return $this->hasOne("App\Hotnews","news_id");
    }

}
