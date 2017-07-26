<?php

namespace App\Http\Controllers\DataFile;


class Factory
{

  public function SetPicture(){
    $img = new \App\Http\Controllers\DataFile\PictureController;
    $img->setFileUpload("picture/original","picture/compress");
    return $img;
  }

  public function SetVideo(){
    $vid = new \App\Http\Controllers\DataFile\VideoController;
    $vid->setFileUpload("videos");
    return $vid;
  }

}
