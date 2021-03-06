<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableChildComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('childcomments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email',80);
            $table->string('name',50);
            $table->text('comment');
            $table->string('ip_address',15);
            $table->timestamps();
        });

        Schema::table('childcomments',function(Blueprint $table){
          $table->integer('comments_id')->unsigned();
          $table->foreign('comments_id')->references('id')->on('comments')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('childcomments');
        Schema::table('childcomments',function(Blueprint $tbl){
          $tbl->dropForeign(['comments_id']);
        });
    }
}
