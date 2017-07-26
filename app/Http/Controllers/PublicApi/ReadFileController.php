<?php

namespace App\Http\Controllers\PublicApi;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DataFile\VideoController;
use Illuminate\Http\Request;
use \App\Http\Controllers\DataFile\Factory as DataFileFactory;

class ReadFileController extends Controller
{

    private function SetPicture(){
      $factory = new DataFileFactory;
      $picture = $factory->SetPicture();
      return $picture;
    }

    private function SetVideo(){
      $factory = new DataFileFactory;
      $video = $factory->SetVideo();
      return $video;
    }

    public function GetOriginalPicture($namepic,$asjson = false){
      $pic = $this->SetPicture();
      $data = $pic->getFileUpload(false,$namepic);
      if ($data['url'] == null) {
        return response()->json(['condition'=>'false','messages'=>'picture not found']);
      }
      $cutURI = substr($data['url'],9);
      $kembali['condition'] = "success";
      $kembali['url'] = url('api/' . $cutURI);
      $kembali['url_raw']=url($data['url']);
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
      $kembali['condition'] = "success";
      $kembali['url'] = url('api/' . $cutURI);
      $kembali['url_raw']=url($data['url']);
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

    public function GetVideo($namevid,$asjson = false){
      $video = $this->SetVideo();
      $data = $video->GetVideoUpload($namevid);
      if ($data['url_video'] == null) {
        return response()->json(['condition'=>'false','messages'=>'video not found']);
      }

      $ext = pathinfo($data['url_video'], PATHINFO_EXTENSION);


      $kembaliVid  = '<video poster="'.$data['url_poster'].'" controls="true">';
        if ($ext == 'mp4') {
          $kembaliVid .= '<source src="'.$data['url_video'].'" type="video/mp4">';
        }elseif ($ext == 'ogg') {
          $kembaliVid .= '<source src="'.$data['url_video'].'" type="video/ogg">';
        }elseif ($ext == 'webm') {
          $kembaliVid .= '<source src="'.$data['url_video'].'" type="video/webm">';
        }
      $kembaliVid .= 'your browser does not support the video tag';
      $kembaliVid .= '</video>';

      $kembaliJson['condition'] = "success";
      $kembaliJson['url_video'] = url($data['url_video']);
      $kembaliJson['url_poster'] = url($data['url_poster']);
      $kembaliJson['title'] = $data['title'];
      $kembaliJson['description'] = $data['description'];
      $kembaliJson['created_at'] = $data['created_at'];
      $kembaliJson['updated_at'] = $data['updated_at'];

      return ($asjson == 'json') ? $kembaliJson : $kembaliVid;
    }


}
