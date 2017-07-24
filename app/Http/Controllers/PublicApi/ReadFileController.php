<?php

namespace App\Http\Controllers\PublicApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReadFileController extends Controller
{

    private function SetPicture(){
      $pic = new \App\Http\Controllers\DataFile\PictureController;
      $pic->setFileUpload("picture/original","picture/compress");
      return $pic;
    }

    public function GetOriginalPicture($namepic,$asjson = false){
      $pic = $this->SetPicture();
      $data = $pic->getFileUpload(false,$namepic);
      if ($data['url'] == null) {
        return response()->json(['condition'=>'false','messages'=>'picture not found']);
      }
      $cutURI = substr($data['url'],9);

      $kembali['url'] = url('api/' . $cutURI);
      $kembali['alt'] = $data['alt'];
      $kembali['title'] = $data['title'];
      $kembali['created_at'] = $data['created_at'];

      return ($asjson == 'json') ? $kembali : '<img src="'.url($data['url']).'" alt="'.$data['alt'].'" title="'.$data['title'].'" />';
    }

    public function GetCompressPicture($namepic,$asjson = false){
      $pic = $this->SetPicture();
      $data = $pic->getFileUpload(true,$namepic);
      if ($data['url'] == null) {
        return response()->json(['condition'=>'false','messages'=>'picture not found']);
      }
      $cutURI = substr($data['url'],9);
      $kembali['url'] = url('api/' . $cutURI);
      $kembali['alt'] = $data['alt'];
      $kembali['title'] = $data['title'];
      $kembali['created_at'] = $data['created_at'];

      return ($asjson == 'json') ? $kembali : '<img src="'.url($data['url']).'" alt="'.$data['alt'].'" title="'.$data['title'].'" />';
    }

    public function GetAllOriginalPicture(){
      $pic = $this->SetPicture();
      return $pic->getAllFileUpload(true);
    }

    public function GetAllCompressPicture(){
      $pic = $this->SetPicture();
      return $pic->getAllFileUpload(false);
    }


}
