<?php

namespace App\Http\Controllers\DataNews;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\News;
use App\Picture;
use App\Category;
use JWTAuth;
use Illuminate\Support\Facades\DB;
class NewsController extends Controller
{

  public function getAllNews(){
    try {

      $news = DB::table('news')
            ->join('categories','news.category_id','=','categories.id')
            ->join('pictures','news.cover','=','pictures.id')
            ->select('news.id','news.title','news.hit','news.short_content','categories.name as category','pictures.namepic as cover','news.created_at','news.updated_at')
            ->orderBy('id','desc')
            ->paginate(15);

    } catch (\Exception $e) {
      return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
    }
    return response()->json(['condition'=>'success','pagination'=>$news]);
  }

  public function getNews($title){
    $hitung = News::where('title',$title)->count();
    if ($hitung == 0) {
      return response()->json(['condition'=>'fail','messages'=>'Article Not Found']);
    }

    if ($hitung > 1) {
      return response()->json(['condition'=>'fail','messages'=>'Something Went Wrong']);
    }

    try {
      $news = DB::table('news')
            ->join('categories','news.category_id','=','categories.id')
            ->join('pictures','news.cover','=','pictures.id')
            ->select('news.id','news.title','news.hit','news.content','categories.name as category','pictures.namepic as cover','news.created_at','news.updated_at')
            ->where('news.title',$title)
            ->get()
            ->toArray();
      $AddHitNews = News::where('title',$title)->first();
      $AddHitNews->hit =  $AddHitNews->hit + 1;
      $AddHitNews->timestamps = false;
      $AddHitNews->save();

    } catch (Exception $e) {
      return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
    }

    return response()->json(['condition'=>'success','data'=>$news]);

  }

  public function insert($req){
    $validasi = $this->validateNews($req);
    if ($validasi != 'success') {
      return $validasi;
    }

    $decrypt = $this->decryptToken($req);
    $create = $this->create($req, $decrypt['id']);
    if ($create != 'success') {
      return $create;
    }

    return response()->json(['condition'=>'success','messages'=>"$req->title Article Has Been Successfully Created"]);
  }

  private function create($req,$byadmin){
    try {
      $getIdPicture = Picture::where('namepic',$req->cover)->first();
      $getIdCategory = Category::where('name',$req->category)->first();
      News::create([
        'title'=>$req->title,
        'hit'=>'0',
        'enablecomment'=>$req->enable_comment,
        'byadmin'=>$byadmin,
        'cover'=>$getIdPicture['id'],
        'category_id'=>$getIdCategory['id'],
        'short_content'=>$req->short_content,
        'content'=>$req->content
      ]);
    } catch (\Exception $e) {
        return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
    }
    return 'success';
  }

  private function validateNews($req){
    $validasi = Validator($req->all(), [
                  'title'=>'required|max:100|unique:news,title',
                  'enable_comment'=>['required',Rule::in(['t','f'])],
                  'cover'=>'required|exists:pictures,namepic',
                  'short_content'=>'required|max:200',
                  'content'=>'required|max:16777000',
                  'category'=>'required|exists:categories,name'
                ]);
    if ($validasi->fails()) {
      return response()->json(['condition'=>'fail','messages'=>$validasi->messages()]);
    }
    return 'success';
  }

  public function edit($id,$req){
    if (!$this->checkIfExist($id)) {
      return $this->NotFoundException();
    }

    $validasi = $this->validateEditNews($req,$id);
    if ($validasi != 'success') {
      return $validasi;
    }

    if ($req->all() == null) {
      return response()->json(['condition'=>'fail','messages'=>'please provide one of these entries : title, enablecomment, cover, content, or category']);
    }

    $update = $this->update($id,$req);
    if ($update != 'success') {
      return $update;
    }

    return response()->json(['condition'=>'success','messages'=>"Article With ID $id Has Been Successfully Edited"]);


  }

  private function update($id,$req){
    try {
      $news = News::where('id',$id)->first();
      if ($req->title != null) {
        $news->title = $req->title;
      }

      if ($req->enable_comment != null) {
        $news->enablecomment = $req->enable_comment;
      }

      if ($req->cover != null) {
        $pic = Picture::where('namepic',$req->cover)->first();
        $news->cover = $pic['id'];
      }

      if ($req->content != null) {
        $news->content = $req->content;
      }

      if ($req->category != null) {
        $cat = Category::where('name',$req->category)->first();
        $news->category_id = $cat['id'];
      }

      if ($req->short_content != null) {
        $news->short_content = $req->short_content;
      }

      $news->save();

    } catch (\Exception $e) {
      return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
    }

    return 'success';

  }

  private function validateEditNews($req,$id){
    $unikTitle="";
    $getTitle = News::where('id',$id)->first()['title'];
    if ($req->title != null && $req->title != $getTitle) {
      $unikTitle = "|unique:news,title";
    }
    $validasi = Validator($req->all(), [
                  'title'=>'max:100'. $unikTitle,
                  'enable_comment'=>[Rule::in(['t','f'])],
                  'cover'=>'exists:pictures,namepic',
                  'category'=>'exists:categories,name',
                  'content'=>'max:16777000',
                  'short_content'=>'max:200'
                ]);
    if ($validasi->fails()) {
      return response()->json(['condition'=>'fail','messages'=>$validasi->messages()]);
    }
    return 'success';
  }

  public function delete($id){
    if (!$this->checkIfExist($id)) {
      return $this->NotFoundException();
    }

    try {
      News::where('id',$id)->delete();
    } catch (\Exception $e) {
      return response()->json(['condition'=>'false','messages'=>$e->getMessage()]);
    }

    return response()->json(['condition'=>'success','messages'=>"Article With ID $id Has Been Successfully Deleted"]);

  }



  private function decryptToken($req){
    try {
      $dataFromToken = JWTAuth::setToken($req->header('Authorization'))->parseToken()->authenticate();

    } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
      return response()->json($e->getMessage(),$e->getStatusCode());
    }
    return $dataFromToken;
  }

  private function checkIfExist($id){
    $hitung = News::where('id',$id)->count();
    if ($hitung > 0) {
      return true;
    }else {
      return false;
    }
  }

  private function NotFoundException(){
    return response()->json(['condition'=>'fail','messages'=>'Article Not Found']);
  }

}
