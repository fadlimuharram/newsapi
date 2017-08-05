<?php

namespace App\Http\Controllers\PrivateApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\DataNews\Factory as DataNewsFactory;

class ReadArticleController extends Controller
{

  public function GetCategories(){
    $factory = new DataNewsFactory;
    $category = $factory->setCategory();
    return $category->getAllCategory();
  }

  public function GetArticles(){
    $factory = new DataNewsFactory;
    $article = $factory->setNews();
    return $article->getAllNews();
  }

  public function ReadArticles($title){
    $factory = new DataNewsFactory;
    $article = $factory->setNews();
    return $article->getNews(str_replace('-',' ',$title));
  }

  public function ReadHotnews(){
    $factory = new DataNewsFactory;
    $hotnews = $factory->setHotNews();
    return $hotnews->getHotNews();
  }

}
