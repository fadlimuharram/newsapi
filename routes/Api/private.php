<?php
/*
  untuk token based api
*/

Route::get('/categories','PrivateApi\ReadArticleController@GetCategories');

Route::get('/articles','PrivateApi\ReadArticleController@GetArticles');
