<?php

namespace App\Http\Controllers\AdminApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\DataNews\Factory as DataNewsFactory;

class ArticleController extends Controller
{

  public function insertCategory(Request $req){
    $factory = new DataNewsFactory;
    $category = $factory->setCategory();
    return $category->insert($req);
  }

  public function editCategory(Request $req,$name){
    $factory = new DataNewsFactory;
    $category = $factory->setCategory();
    return $category->edit(str_replace('-',' ',$name),$req);
  }

  public function deleteCategory($name){
    $factory = new DataNewsFactory;
    $category = $factory->setCategory();
    return $category->delete(str_replace('-',' ',$name));
  }

  public function insertNews(Request $req){
    $factory = new DataNewsFactory;
    $news = $factory->setNews();
    return $news->insert($req);
  }

  public function editNews(Request $req,$id){
    $factory = new DataNewsFactory;
    $news = $factory->setNews();
    return $news->edit($id,$req);
  }

  public function deleteNews($id){
    $factory = new DataNewsFactory;
    $news = $factory->setNews();
    return $news->delete($id);
  }


}
