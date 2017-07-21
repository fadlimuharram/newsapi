<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_profiles', function (Blueprint $table) {
              $table->increments('id');
              $table->string('name',50);
        });

        Schema::table('public_profiles',function(Blueprint $tbl){
          $tbl->integer('user_id')->unsigned();
          $tbl->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('public_profiles');

        Schema::table('public_profiles',function(Blueprint $tbl){
          $tbl->dropForeign(['user_id']);
        });
    }
}
