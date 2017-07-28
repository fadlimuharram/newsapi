<?php

namespace App\Http\Controllers\AdminApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\DataFile\Factory as DataFileFactory;
class FilesController extends Controller
{

    public function uploadPicture(Request $req){
      $factory = new DataFileFactory;
      $picture = $factory->SetPicture();
      return $picture->fileUpload($req);
    }

    public function deletePicture($namepic){
      $factory = new DataFileFactory;
      $picture = $factory->SetPicture();
      return $picture->deleteFile($namepic);
    }

    public function uploadVideo(Request $req){
      $factory = new DataFileFactory;
      $video = $factory->SetVideo();
      return $video->fileUpload($req);
    }

    public function editVideo(Request $req,$namevid){
      $factory = new DataFileFactory;
      $video = $factory->SetVideo();
      return $video->editVideo($namevid,$req);
    }

    public function deleteVideo($namevid){
      $factory = new DataFileFactory;
      $video = $factory->SetVideo();
      return $video->deleteVideo($namevid);
    }


}
