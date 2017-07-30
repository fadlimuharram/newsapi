<?php

namespace App\Http\Controllers\DataFile;

use App\Http\Controllers\Controller;
use App\Pilihan_video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Video;
use Illuminate\Support\Facades\Storage;

class PilihanVideoController
{

  private $IDVideo = array();
  private $dir;
  private $dirPoster;

  public function setVideoLocation($dir){
    $this->dir = $dir;
    $this->dirPoster = $this->dir . "/" . "poster";
  }

  public function GetPilihanVideo(){
    try {
      $pilihan = \App\Pilihan_video::with('Videos')->get()->toArray();
    } catch (\Exception $e) {
      return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
    }
    return response()->json(['condition'=>'success',
                             'base_url_video'=>url(Storage::url($this->dir)),
                             'base_url_poster'=>url(Storage::url($this->dirPoster)),
                             'data'=>$pilihan]);
  }

  public function pilihanVideos($req){
    $validasi = $this->ValidatePilihan($req);
    if ($validasi != 'success') {
      return $validasi;
    }

    $deleteall = $this->DeleteAllPilihan();
    if ($deleteall != 'success') {
      return $deleteall;
    }

    $cekvid = $this->CheckVideo($req);
    if ($cekvid != 'success') {
      return $cekvid;
    }

    $insert = $this->insertPilihan();
    if ($insert != 'success') {
      return $insert;
    }

    return response()->json(['condition'=>'success','messages'=>'Three Videos Successfully Selected']);
  }


  private function ValidatePilihan($req){
    $validasi = Validator($req->all(), [
                  'first' => 'required|string',
                  'second' => 'required|string',
                  'third'=>'required|string',
                ]);

    $kembali['condition'] = 'fail';
    $kembali['messages'] = $validasi->messages();
    return ($validasi->fails()) ? response()->json($kembali) : 'success';
  }

  private function insertPilihan(){
    try {

      foreach ($this->IDVideo as $key => $value) {
        \App\Pilihan_video::create([
          'videos_id'=>$value
        ]);
      }

    } catch (\Exception $e) {
      return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
    }

    return "success";
  }

  private function DeleteAllPilihan(){
    try {
      \App\Pilihan_video::truncate();
    } catch (\Exception $e) {
      return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
    }
    return "success";
  }

  private function CheckVideo($req){
    foreach ($req->all() as $key => $value) {
      $video = Video::where('namevid',$value);
      if ($video->count() == 0) {
        return response()->json(['condition'=>'fail','messages'=>"Video $value Not Found"]);
      }else {
        $dataVideo = $video->get()->toArray();
        $this->IDVideo($value,$dataVideo[0]['id']);
      }
    }
    return "success";
  }

  private function IDVideo($key,$val){
    $this->IDVideo[$key] = $val;
  }



}
