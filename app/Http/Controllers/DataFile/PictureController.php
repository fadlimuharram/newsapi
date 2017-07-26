<?php

namespace App\Http\Controllers\DataFile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use App\Picture;
use JWTAuth;

class PictureController
{
    private $dir;
    private $dirCompress;


    public function setFileUpload($dir,$dircompress){
      $this->dir = $dir;
      $this->dirCompress = $dircompress;
    }

    /*
    jika compress true maka akan return gambar yang di compress
    */
    public function getFileUpload($compress = true,$name){
      $pic = Picture::where('namepic',$name);
      $hitung = $pic->count();
      $data = $pic->get()->toArray();
      if ($hitung == 1) {
        if ($compress) {
          $url = Storage::url($this->dirCompress."/".$name);
        }else {
          $url = Storage::url($this->dir."/".$name);
        }
        return ['url'=>$url,
                'alt'=>$data[0]['altpic'],
                'title'=>$data[0]['titlepic'],
                'created_at'=>$data[0]['created_at']
              ];
      }
      if ($hitung > 1) {
        return response()->json(['condition'=>'fail','messages'=>'multiple pictures found!, please contact web master']);
      }
    }

    /*
    jika compress true maka akan return gambar yang di compress
    */
    public function getAllFileUpload($compress = true){
      $hitung = Picture::count();
      if ($hitung > 0) {
        if ($compress) {
          $image_base_url = $this->dir;
        }else {
          $image_base_url = $this->dirCompress;
        }
        return response()->json(['condition'=>'success','pagination'=>Picture::paginate()]);
      }else {
        return response()->json(['condition'=>'fail','messages'=>'images not found']);
      }
    }


    public function fileUpload($req){
      if ($req->hasFile('image') && $req->file('image')->isValid()) {
        $validasi = $this->validatePicture($req);
        if ($validasi == 'success') {
          $original = $this->OriginalPicture($req);
          $resize = $this->resizePicture($req);

          $dec = $this->decryptToken($req);

          if ($insert = $this->InsertData($req,substr($original,10),$dec) == 'success') {
            return response()->json([
              'original'=>[
              'condition'=>'success',
              'messages'=>'Original File successfully uploaded',
              'location'=>url('api/picture'.$original.'/true')
            ],
              'compress'=>[
              'condition'=>'success',
              'messages'=>'Compress File successfully uploaded',
              'location'=>url('api/picture'.$resize.'/true')
              ]
            ]);
          }else {
            return $insert;
          }


        }else {
          return $validasi;
        }

      }else {
        return response()->json(['condition'=>'fail','messages'=>'image(jpeg,png,jpg,gif,svg) is required']);
      }

    }

    private function decryptToken($req){
      try {
        $dataFromToken = JWTAuth::setToken($req->header('Authorization'))->parseToken()->authenticate();

      } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        return response()->json($e->getMessage(),$e->getStatusCode());
      }
      return $dataFromToken;
    }

    private function InsertData($req,$namepic,$byadmin){
      try {
        Picture::create([
          'namepic'=>$namepic,
          'altpic'=>($req->alt) ? $req->alt : null,
          'titlepic'=>($req->title) ? $req->title : null,
          'byadmin'=>$byadmin['id']
        ]);
      } catch (\Exception $e) {
        return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
      }
      return 'success';
    }

    private function validatePicture($req){
      $validasi = Validator($req->all(), [
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                    'alt'=>'string|max:100',
                    'title'=>'string|max:100'
                  ]);
      if ($validasi->fails()) {
        return response()->json(['condition'=>'fail','messages'=>$validasi->messages()]);
      }
      return 'success';
    }

    private function OriginalPicture($req){
      try {
        $path = Storage::putFile("public/" . $this->dir, $req->file('image'));
      } catch (\Exception $e) {
        return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
      }
      return substr($path, 14);
    }

    private function resizePicture($req){
      $getSize = getimagesize($req->file('image'));
      $filePic = Image::make($req->file('image'));

      $path = $req->file('image')->hashName("public/" . $this->dirCompress);

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

      return substr($path, 14);
    }

    public function deleteFile($name){
      $hitung = Picture::where('namepic',$name)->count();
      if ($hitung == 1) {
        try {
          Storage::delete("public/" . $this->dir ."/" . $name);
          Storage::delete("public/" . $this->dirCompress ."/" . $name);
          Picture::where('namepic',$name)->delete();
        } catch (\Exception $e) {
          return response()->json(['condition'=>'fail','messages'=>$e->getMessage()]);
        }
        return response()->json(['condition'=>'success','messages'=>"Picture with name " . $name . " successfully deleted"]);
      }else {
        return response()->json(['condition'=>'fail','messages'=>'Image Not Found']);
      }
    }
}
