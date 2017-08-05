<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHotNews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotnews', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('created_at');
        });

        Schema::table('hotnews',function(Blueprint $table){
          $table->integer('news_id')->unsigned();
          $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotnews');
        Schema::table('hotnews',function(Blueprint $tbl){
          $tbl->dropForeign(['news_id']);
        });
    }
}
