<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('news', function (Blueprint $table) {
          $table->increments('id');
          $table->string('title',100);
          $table->integer('hit')->unsigned();
          $table->enum('enablecomment',['t','f']);
          $table->integer('byadmin')->unsigned();
          $table->timestamps();
      });

      Schema::table('news',function(Blueprint $tbl){
        $tbl->integer('cover')->unsigned();
        $tbl->foreign('cover')->references('id')->on('pictures');
        $tbl->integer('catagory_id')->unsigned();
        $tbl->foreign('catagory_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}
