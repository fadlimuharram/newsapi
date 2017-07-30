<?php

namespace App\Http\Controllers\DataFile;


class Factory
{
  private $locVideo = "videos";
  private $locPictureOriginal = "picture/original";
  private $locPictureCompress = "picture/compress";

  public function SetPicture(){
    $img = new \App\Http\Controllers\DataFile\PictureController;
    $img->setFileUpload($this->locPictureOriginal,$this->locPictureCompress);
    return $img;
  }

  public function SetVideo(){
    $vid = new \App\Http\Controllers\DataFile\VideoController;
    $vid->setFileUpload($this->locVideo);
    return $vid;
  }

  public function setPilihanVideo(){
    $pilih = new \App\Http\Controllers\DataFile\PilihanVideoController;
    $pilih->setVideoLocation($this->locVideo);
    return $pilih;
  }

}
