<?php

namespace App\Http\Controllers\DataFile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

class PictureController extends Controller
{
    private $dir = "gambar/original";
    private $dirCompress = "gambar/compress";

    public function fileUpload(Request $req){
      if ($req->hasFile('image') && $req->file('image')->isValid()) {
        $validasi = $this->validatePicture($req);

        if ($validasi == 'success') {
          $original = $this->OriginalPicture($req);
          $resize = $this->resizePicture($req);
          return response()->json(['uncompress'=>$original,'compress'=>$resize]);
        }else {
          return $validasi;
        }

      }else {
        return response()->json(['condition'=>'fail','messages'=>'terjadi kesalahan']);
      }

    }

    private function validatePicture($req){
      $validasi = Validator($req->all(), [
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240'
                  ]);
      if ($validasi->fails()) {
        return response()->json(['condition'=>'fail','messages'=>$validasi->messages()]);
      }
      return 'success';
    }

    private function OriginalPicture($req){
      return Storage::putFile("public/" . $this->dir, $req->file('image'));
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
      return Storage::put($path, (string) $filePic->encode());
    }
}
