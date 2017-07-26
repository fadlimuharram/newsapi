<?php
namespace App\Http\Controllers\DataFile;

use Illuminate\Http\Request;
use App\Video;
use JWTAuth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class VideoController
{
    private $dir;
    private $dirPoster;

    public function setFileUpload($dir){
      $this->dir = $dir;
      $this->dirPoster = $this->dir . "/" . "poster";
    }

    public function fileUpload($request){
      if ($request->hasFile('poster') && $request->hasFile('video')) {
        $validasi = $this->validateVideos($request);
        if ($validasi  == 'success') {

          $dataFromToken = $this->decryptToken($request);
          $namePoster = $this->uploadPoster($request);
          $nameVideo = $this->uploadVideo($request);
          $insertVideo = $this->insertVideo($nameVideo, $namePoster, $request, $dataFromToken);
          if ($insertVideo == "success") {
            return response()->json(['condition'=>'success',
                                     'name_video'=>$nameVideo,
                                     'name_poster'=>$namePoster
                                   ]);
          }else {
            return $insertVideo;
          }

        }else {
          return $validasi;
        }
      }else {
        return response()->json(['condition'=>'fail','messages'=>'video(mp4,webm,ogg) and poster(jpeg,png,jpg,gif,svg) is required']);
      }


    }

    private function validateVideos($req){
      $validasi = Validator($req->all(), [
                    'video' => 'required|mimes:mp4,webm,ogg|max:40960',
                    'poster'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
                    'title'=>'required|string|max:100',
                    'description'=>'required|string'
                  ]);
      if ($validasi->fails()) {
          return response()->json(['condition'=>'fail','messages'=>$validasi->messages()]);
      }
      return 'success';
    }

    private function uploadPoster($req){

      $getSize = getimagesize($req->file('poster'));
      $filePic = Image::make($req->file('poster'));

      $path = $req->file('poster')->hashName("public/" . $this->dirPoster);

      if ($getSize[0] > 300) {
        $filePic->resize(300,null,function($constraint){
          $constraint->aspectRatio();
        });
      }

      if ($getSize[1] > 300) {
        $filePic->resize(null,300,function($constraint){
          $constraint->aspectRatio();
        });
      }

      try {
        Storage::put($path, (string) $filePic->encode());
      } catch (\Exception $e) {
        return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
      }

      return substr($path, 21);
    }

    private function decryptToken($req){
      try {
        $dataFromToken = JWTAuth::setToken($req->header('Authorization'))->parseToken()->authenticate();

      } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        return response()->json($e->getMessage(),$e->getStatusCode());
      }
      return $dataFromToken;
    }

    private function uploadVideo($req){
      try {
        $path = Storage::putFile("public/" . $this->dir, $req->file('video'));
      } catch (\Exception $e) {
        return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
      }
      return substr($path, 14);
    }

    private function insertVideo($nameVideo,$namePoster,$req,$byAdmin){
      try {
        Video::create([
          'namevid'=>$nameVideo,
          'poster'=>$namePoster,
          'title'=>$req->title,
          'description'=>$req->description,
          'byadmin'=>$byAdmin['id']
        ]);
      } catch (\Exception $e) {
        return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
      }
      return "success";

    }


}
