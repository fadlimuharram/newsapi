<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePilihanVideos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pilihan_videos', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::table('pilihan_videos',function(Blueprint $tbl){
          $tbl->integer('videos_id')->unsigned();
          $tbl->foreign('videos_id')->references('id')->on('videos')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pilihan_videos');
        
        Schema::table('pilihan_videos',function(Blueprint $tbl){
          $tbl->dropForeign(['videos_id']);
        });
    }
}
