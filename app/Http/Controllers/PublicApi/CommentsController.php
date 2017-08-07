<?php

namespace App\Http\Controllers\PublicApi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DataFile\VideoController;
use Illuminate\Http\Request;
use \App\Http\Controllers\DataFile\Factory as DataFileFactory;
use App\Comment;
use App\News;
use \Carbon\Carbon;

class CommentsController extends Controller
{

  public function getcomments($title){
    try {
      $news = News::where('title',str_replace('-',' ',$title))->first();
      $getid = $news['id'];
      if ($news['enablecomment'] == 'f') {
        return response()->json(['condition'=>'success','messages'=>'Comment Feature Is Disable']);
      }
      if ($getid == null) {
        return response()->json(['condition'=>'fail','messages'=>'Article Not Found']);
      }
      $getcomment = Comment::where('news_id',$getid)->with('ChildComments');
    } catch (\Exception $e) {
      return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
    }
    return response()->json(['condition'=>'success','pagination'=>$getcomment->paginate(15)]);
  }

  public function insert(Request $req){
    $cek = $this->checkRequest($req);
    if ($cek != 'pass') {
      return $cek;
    }

    $validasi = $this->validateComment($req);
    if ($validasi != 'success') {
      return $validasi;
    }

    $create = $this->create($req);
    if ($create != 'success') {
      return $create;
    }

    return response()->json(['condition'=>'success','messages'=>'Comment Successfully Inserted']);

  }

  private function create($req){
    try {
      Comment::create([
        'email'=>$req->email,
        'name'=>$req->name,
        'comment'=>$req->comment,
        'ip_address'=>$req->ip(),
        'news_id'=>$req->news_id
      ]);
    } catch (\Exception $e) {
        return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
    }
    return 'success';
  }

  //mencegah penginputan berkali kali
  private function checkRequest($req){
    $getComment = Comment::where('ip_address',$req->ip())->orderBy('id','desc')->get()->toArray();

    $first = (isset($getComment[0])) ? strtotime($getComment[0]['created_at']) : null;
    $second = (isset($getComment[1])) ? strtotime($getComment[1]['created_at']) : null;
    if ($first == null) {
      return 'pass';
    }

    $now = Carbon::now()->timestamp;

    if (($now - $first < 180) || ($now - $second < 200)) {
      $wait = ($now - $second) - 200;
      return response()->json(['condition'=>'fail','messages'=>"Too Many Request You Need To Wait For $wait Second"]);
    }

    return 'pass';
  }

  private function validateComment($req){
    $validasi = Validator($req->all(), [
                  'email'=>'required|email|max:80',
                  'name'=>'required|max:50',
                  'comment'=>'required',
                  'news_id'=>'required|exists:news,id'
                ]);
    if ($validasi->fails()) {
      return response()->json(['condition'=>'fail','messages'=>$validasi->messages()]);
    }
    return 'success';

  }

  public function delete($id){
    $comment = Comment::where('id',$id);
    $hitung = $comment->count();
    if ($hitung < 1) {
      return response()->json(['condition'=>'fail','messages'=>'Comment Not Found']);
    }

    try {
      $comment->delete();
    } catch (\Exception $e) {
      return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
    }

    return response()->json(['condition'=>'success','messages'=>"Comment With ID $id Has Been Successfully Deleted"]);

  }

}
