<?php

namespace App\Http\Controllers\AdminApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\DataFile\PictureController;
use App\Http\Controllers\DataFile\VideoController;
class FilesController extends Controller
{

    public function uploadPicture(Request $req){
      $img = new PictureController;
      $img->setFileUpload("picture/original","picture/compress");
      return $img->fileUpload($req);
    }

    public function deletePicture($namepic){
      $img = new PictureController;
      $img->setFileUpload("picture/original","picture/compress");
      return $img->deleteFile($namepic);
    }

    public function uploadVideo(Request $req){

    }


}
