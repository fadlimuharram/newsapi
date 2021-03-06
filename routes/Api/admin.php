<?php
/*
admin api
*/
Route::post('/register','AdminApi\RegisterController@register');

Route::post('/picture/upload','AdminApi\FilesController@uploadPicture');
Route::delete('/picture/delete/{namepic}','AdminApi\FilesController@deletePicture');

Route::post('/video/upload','AdminApi\FilesController@uploadVideo');
Route::patch('/video/edit/{namevid}','AdminApi\FilesController@editVideo');
Route::delete('/video/delete/{namevid}','AdminApi\FilesController@deleteVideo');
Route::post('/video/pilihan','AdminApi\FilesController@pilihanVideos');

Route::post('/category/insert','AdminApi\ArticleController@insertCategory');
Route::patch('/category/edit/{name}','AdminApi\ArticleController@editCategory');
Route::delete('/category/delete/{name}','AdminApi\ArticleController@deleteCategory');

Route::post('/article/insert','AdminApi\ArticleController@insertNews');
Route::patch('/article/edit/{id}','AdminApi\ArticleController@editNews');
Route::delete('/article/delete/{id}','AdminApi\ArticleController@deleteNews');

Route::post('/article/hotnews/insert','AdminApi\ArticleController@insertHotNews');
Route::delete('/article/hotnews/delete/{title}','AdminApi\ArticleController@deleteHotNews');

Route::delete('/comment/delete/{id}','PublicApi\CommentsController@delete');
Route::delete('/childcomment/delete/{id}','PublicApi\ChildCommentsController@delete');
