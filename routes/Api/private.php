<?php
/*
  untuk token based api
*/
Route::get('/tesaja',function(){
  return response()->json(['token is good']);
});

Route::get('/categories','PrivateApi\ReadArticleController@GetCategories');
