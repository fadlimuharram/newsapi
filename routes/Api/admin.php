<?php
/*
admin api
*/
Route::post('/register','AdminApi\RegisterController@register');
Route::post('/picture/upload','AdminApi\FilesController@uploadPicture');
