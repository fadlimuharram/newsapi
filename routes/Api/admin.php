<?php
/*
admin api
*/
Route::post('/register','AdminApi\RegisterController@register');
Route::post('/picture/upload','AdminApi\FilesController@uploadPicture');
Route::delete('/picture/delete/{namepic}','AdminApi\FilesController@deletePicture');
Route::post('/video/upload','AdminApi\FilesController@uploadVideo');
Route::patch('/video/edit/{namevid}','AdminApi\FilesController@editVideo');
