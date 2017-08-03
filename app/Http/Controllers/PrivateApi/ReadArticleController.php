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

}
