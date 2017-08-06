<?php

namespace App\Http\Controllers\PublicApi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DataFile\VideoController;
use Illuminate\Http\Request;
use \App\Http\Controllers\DataFile\Factory as DataFileFactory;
use App\Comment;
use App\ChildComments;
use \Carbon\Carbon;

class ChildCommentsController extends Controller
{

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

    return response()->json(['condition'=>'success','messages'=>'Child Comment Successfully Inserted']);

  }

  private function create($req){
    try {
      ChildComments::create([
        'email'=>$req->email,
        'name'=>$req->name,
        'comment'=>$req->comment,
        'ip_address'=>$req->ip(),
        'comments_id'=>$req->comments_id
      ]);
    } catch (\Exception $e) {
        return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
    }
    return 'success';
  }

  //mencegah penginputan berkali kali
  private function checkRequest($req){
    $getComment = ChildComments::where('ip_address',$req->ip())->orderBy('id','desc')->get()->toArray();
  
    $first = (isset($getComment[0])) ? strtotime($getComment[0]['created_at']) : null;
    $second = (isset($getComment[1])) ? strtotime($getComment[1]['created_at']) : null;
    if ($first == null) {
      return 'pass';
    }

    $now = Carbon::now()->timestamp;

    if (($now - $first < 60) || ($now - $second < 80)) {
      $wait = 60 - ($now - $first);
      return response()->json(['condition'=>'fail','messages'=>"Too Many Request You Need To Wait For $wait Second"]);
    }

    return 'pass';
  }

  private function validateComment($req){
    $validasi = Validator($req->all(), [
                  'email'=>'required|email|max:80',
                  'name'=>'required|max:50',
                  'comment'=>'required',
                  'comments_id'=>'required|exists:comments,id'
                ]);
    if ($validasi->fails()) {
      return response()->json(['condition'=>'fail','messages'=>$validasi->messages()]);
    }
    return 'success';

  }

  public function delete($id){
    $comment = ChildComments::where('id',$id);
    $hitung = $comment->count();
    if ($hitung < 1) {
      return response()->json(['condition'=>'fail','messages'=>'Comment Not Found']);
    }

    try {
      $comment->delete();
    } catch (\Exception $e) {
      return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
    }

    return response()->json(['condition'=>'success','messages'=>"Child Comment With ID $id Has Been Successfully Deleted"]);

  }

}
