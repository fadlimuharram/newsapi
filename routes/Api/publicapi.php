<?php
/*
  public api
  untuk : login
*/
Route::post('/login','PublicApi\LoginController@AttemptLogin');

Route::get('/picture/original/{namepic}/{asjson?}','PublicApi\ReadFileController@GetOriginalPicture');
Route::get('/picture/compress/{namepic}/{asjson?}','PublicApi\ReadFileController@GetCompressPicture');
Route::get('/pictures/original','PublicApi\ReadFileController@GetAllOriginalPicture');
Route::get('/pictures/compress','PublicApi\ReadFileController@GetAllCompressPicture');

Route::get('/video/{namevid}/{asjson?}','PublicApi\ReadFileController@GetVideo');
Route::get('/videos','PublicApi\ReadFileController@GetAllVideo');
Route::get('/pilihan/video','PublicApi\ReadFileController@GetPilihanVideo');

Route::post('/comment/insert','PublicApi\CommentsController@insert');
Route::post('/childcomment/insert','PublicApi\ChildCommentsController@insert');
