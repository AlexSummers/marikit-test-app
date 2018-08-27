<?php

Route::get('/', 'AppController@get');
Route::get('/news', 'AppController@get');
Route::get('/news/{id}', 'AppController@get');
Route::get('/products', 'AppController@get');

Route::resource('/load-news', 'NewsController', ['only' => ['index', 'show']]);
