<?php

Route::group(['domain' => 'image.' . Request::server("SERVER_NAME")], function () {

    Route::get('{variant}/{path}', array('uses' => 'Ognestraz\Image\Http\Controllers\ImageController@image'));
    
});
