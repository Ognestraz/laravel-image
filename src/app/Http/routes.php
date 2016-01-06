<?php

Route::group(['domain' => 'image.*'], function () {
    
    Route::get('{variant}/{path}', array('uses' => 'App\Http\Controllers\ImageController@image'));
    
});
