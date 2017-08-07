<?php

namespace App\Http\Controllers\DataNews;

use App\Http\Controllers\Controller;
use App\Category;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

  public function insert($req){

    $validasi = $this->validateCategory($req);
    if ($validasi != 'success') {
      return $validasi;
    }

    if ($this->checkIfExist($req->name) == true) {
      return $this->ExistsException();
    }

    $create = $this->create($req);

    if ($create != 'success') {
      return $create;
    }

    return response()->json(['condition'=>'success','messages'=>"$req->name Category Has Been Successfully Inserted"]);

  }

  private function create($req){
    try {
      Category::create([
        'name'=>$req->name
      ]);
    } catch (\Exception $e) {
        return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
    }
    return 'success';
  }

  public function edit($OldName,$req){
    $validasi = $this->validateCategory($req);
    if ($validasi != 'success') {
      return $validasi;
    }

    if ($this->checkIfExist($OldName) == true) {
      if ($this->checkIfExist($req->name) == true) {
        return $this->ExistsException();
      }else {
        $update = $this->update($OldName,$req);
        if ($update != 'success') {
          return $update;
        }
        return response()->json(['condition'=>'success','messages'=>"The $OldName Catagory Has Been Successfully Changed To $req->name"]);
      }
    }else {
      return $this->NotFoundException();
    }
  }

  private function update($OldName,$req){
    try {
      $category = Category::where('name',$OldName)->first();
      $category->name = $req->name;
      $category->save();
    } catch (\Exception $e) {
      return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
    }
    return 'success';
  }

  public function delete($name){

    if ($this->checkIfExist($name) == true) {
      try {
        Category::where('name',$name)->delete();
      } catch (\Exception $e) {
        return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
      }

      return response()->json(['condition'=>'success','messages'=>"Category $name Has Been Successfully Deleted"]);

    }else {
      return $this->NotFoundException();
    }

  }

  public function getAllCategory(){
    try {

      $category = Category::get()->toArray();

    } catch (\Exception $e) {
      return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
    }
    return response()->json(['condition'=>'success','data'=>$category]);
  }

  public function getArticles($categoryname){
    try {

      $news = DB::table('news')
            ->join('categories','news.category_id','=','categories.id')
            ->join('pictures','news.cover','=','pictures.id')
            ->select('news.id','news.title','news.hit','news.short_content','categories.name as category','pictures.namepic as cover','news.created_at','news.updated_at')
            ->where('categories.name',$categoryname)
            ->orderBy('id','desc')
            ->paginate(15);

    } catch (\Exception $e) {
      return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
    }
    return response()->json(['condition'=>'success','pagination'=>$news]);
  }

  private function validateCategory($req){
    $validasi = Validator($req->all(), [
                  'name'=>'required|max:50'
                ]);
    if ($validasi->fails()) {
      return response()->json(['condition'=>'fail','messages'=>$validasi->messages()]);
    }
    return 'success';
  }

  private function checkIfExist($name){
    $hitung = Category::where('name',$name)->count();
    if ($hitung > 0) {
      return true;
    }else {
      return false;
    }
  }

  private function NotFoundException(){
    return response()->json(['condition'=>'fail','messages'=>'Category Not Found']);
  }

  private function ExistsException(){
    return response()->json(['condition'=>'fail','messages'=>'Category Already Exists']);
  }


}
