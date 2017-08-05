<?php

namespace App\Http\Controllers\DataNews;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\News;
use App\Hotnews;
use JWTAuth;
use Illuminate\Support\Facades\DB;

class HotNewsController extends Controller
{

  private $maxvideo = 15;

  public function getHotNews(){
    try {
      $hotnews = DB::table('hotnews')
                 ->join('news','hotnews.id','=','news.id')
                 ->join('categories','news.category_id','=','categories.id')
                 ->join('pictures','news.cover','=','pictures.id')
                 ->select('hotnews.id as hotnews_id', 'hotnews.created_at as hotnews_created_at', 'news.id as news_id','news.title as news_title','news.hit as news_hit','news.short_content as news_short_content','categories.name as category','pictures.namepic as cover','news.created_at as news_created_at','news.updated_at as news_updated_at')
                 ->get()
                 ->toArray();

    } catch (\Exception $e) {
      return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
    }
    return response()->json(['condition'=>'success','data'=>$hotnews]);
  }

  public function insert($req){

    $validasi = $this->validateHotNews($req);
    if ($validasi != 'success') {
      return $validasi;
    }

    $checkduplicate = $this->checkDuplicate($req);

    if ($checkduplicate) {
      return response()->json(['condition'=>'fail','messages'=>"$req->hotnews is already exists"]);
    }

    if (Hotnews::count() > $this->maxvideo) {
      $delete = $this->deleteOldSelectedNews();
      if ($delete != 'success') {
        return $delete;
      }
    }

    $create = $this->create($req);

    if ($create != 'success') {
      return $create;
    }
    return response()->json(['condition'=>'success','messages'=>"$req->hotnews Has Been Selected As Hot News"]);

  }

  private function create($req){
    try {
      $getid = News::where('title',$req->hotnews)->first()['id'];
      Hotnews::create([
        'created_at'=>\Carbon\Carbon::now()->toDateTimeString(),
        'news_id'=>$getid
      ]);
    } catch (\Exception $e) {
      return response()->json(['condition'=>'false','messages'=>$e->getMessage()]);
    }
    return 'success';
  }

  private function deleteOldSelectedNews(){
    try {
      Hotnews::where('id',Hotnews::min('id'))->delete();
    } catch (\Exception $e) {
      return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
    }
    return 'success';
  }

  private function validateHotNews($req){
    $validasi = Validator($req->all(), [
                  'hotnews'=>'required|exists:news,title'
                ]);
    if ($validasi->fails()) {
      return response()->json(['condition'=>'fail','messages'=>$validasi->messages()]);
    }
    return 'success';
  }

  private function checkDuplicate($req){
    $hitung = Hotnews::where('news_id',News::where('title',$req->hotnews)->first()['id'])->count();
    if ($hitung > 0) {
      return true;
    }
    return false;
  }

  public function delete($title){
    $news = Hotnews::where('news_id',News::where('title',$title)->first()['id']);
    $hitung = $news->count();
    if ($hitung == 0) {
      return response()->json(['condition'=>'fail','messages'=>"Hotnews With Title $title Not Found"]);
    }

    try {
      $news->delete();
    } catch (\Exception $e) {
      return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
    }
    return response()->json(['condition'=>'success','messages'=>"Unselect $title as hot news"]);
  }


}
