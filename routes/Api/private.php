<?php
/*
  untuk token based api
*/

Route::get('/categories','PrivateApi\ReadArticleController@GetCategories');

Route::get('/article/category/{category}','PrivateApi\ReadArticleController@GetArticleByCategory');

Route::get('/articles','PrivateApi\ReadArticleController@GetArticles');

Route::get('/read/{title}','PrivateApi\ReadArticleController@ReadArticles');

Route::get('/hotnews','PrivateApi\ReadArticleController@ReadHotnews');

Route::get('/comments/{title}','PublicApi\CommentsController@getcomments');
