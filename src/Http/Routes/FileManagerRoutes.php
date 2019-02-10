<?php

Route::group([
    'prefix' => config('file-manager.route_prefix'), //'api/uf',
    'middleware' => 'api',
    'namespace' => 'Larangular\FileManager\Http\Controllers',
    'as' => 'larangular.api.file-manager.'
], function () {
    //Route::get('/{id}', 'FileManager\Gateway@show');
    Route::get('/file/{fileManager}/{name}', 'FileManager\Gateway@showFile');
    Route::resource('/file', 'FileManager\Gateway');
});
