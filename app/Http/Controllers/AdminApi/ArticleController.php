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


}
