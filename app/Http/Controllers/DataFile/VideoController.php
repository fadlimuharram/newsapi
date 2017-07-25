<?php

namespace App\Http\Controllers\DataFile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    private $dir;

    public function setFileUpload($dir){
      $this->dir = $dir;
    }

    public function fileUpload($request){

    }

    private function validateVideos($req){
      $validasi = Validator($req->all(), [
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                    'alt'=>'string|max:100',
                    'title'=>'string|max:100'
                  ]);
    }


}
