<?php

Route::group([
    'prefix'     => config('file-manager.route_prefix', 'api/file-manager'),
    'middleware' => 'api',
    'namespace'  => 'Larangular\FileManager\Http\Controllers',
    'as'         => 'larangular.api.file-manager.',
], function () {
    Route::get('/file/{fileManager}/{name}', 'FileManager\Gateway@showFile')->name('show');
    Route::resource('/file', 'FileManager\Gateway');
    Route::resource('file-relationship', 'FileRelationship\Gateway');
});
