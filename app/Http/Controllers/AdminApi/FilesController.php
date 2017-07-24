<?php

namespace App\Http\Controllers\AdminApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\DataFile\PictureController;

class FilesController extends Controller
{

    public function uploadPicture(Request $req){
      $img = new PictureController;
      $img->setFileUpload("picture/original","picture/compress");
      return $img->fileUpload($req);
    }


}
