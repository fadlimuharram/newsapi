<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email',80);
            $table->string('name',50);
            $table->text('comment');
            $table->string('ip_address',15);
            $table->timestamps();
        });

        Schema::table('comments',function(Blueprint $table){
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
        Schema::dropIfExists('comments');
        Schema::table('comments',function(Blueprint $tbl){
          $tbl->dropForeign(['news_id']);
        });
    }
}
