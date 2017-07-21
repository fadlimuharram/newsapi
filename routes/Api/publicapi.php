<?php
/*
  public api
  untuk : login
*/
Route::post('/login','PublicApi\LoginController@AttemptLogin');
