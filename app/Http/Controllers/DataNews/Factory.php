<?php

namespace App\Http\Controllers\DataNews;

use App\Http\Controllers\Controller;
use App\Category;


class Factory extends Controller
{

  public function setCategory(){
    $cat = new \App\Http\Controllers\DataNews\CategoryController;
    return $cat;
  }

  public function setNews(){
    $news = new \App\Http\Controllers\DataNews\NewsController;
    return $news;
  }


}
