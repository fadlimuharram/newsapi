<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pilihan_video extends Model
{
  protected $table = 'pilihan_videos';

  protected $fillable = [
     'videos_id'
  ];
  public $timestamps = false;

  public function Videos(){
    return $this->belongsTo("App\Video","videos_id");
  }
}
