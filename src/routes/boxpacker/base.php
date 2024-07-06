<?php

use Illuminate\Support\Facades\Route;

Route::get('package/box-packer', 'Jmursuadev\BoxPacker\Controllers\BoxPackerController@index');
Route::post('api/package/pack', 'Jmursuadev\BoxPacker\Controllers\BoxPackerController@pack');
