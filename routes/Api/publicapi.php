<?php
/*
  public api
  untuk : login
*/
Route::post('/login','PublicApi\LoginController@AttemptLogin');
Route::get('/picture/original/{namepic}/{asjson?}','PublicApi\ReadFileController@GetOriginalPicture');
Route::get('/picture/compress/{namepic}/{asjson?}','PublicApi\ReadFileController@GetCompressPicture');
Route::get('/picture/all/original','PublicApi\ReadFileController@GetAllOriginalPicture');
Route::get('/picture/all/compress','PublicApi\ReadFileController@GetAllCompressPicture');
Route::get('/video/{namevid}/{asjson?}','PublicApi\ReadFileController@GetVideo');
