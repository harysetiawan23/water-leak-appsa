<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    //Add you routes here, for example:
    return view('welcome');
});

Route::group([
    'middleware' => ['cors']
], function ($router) {
    //Add you routes here, for example:
    return view('welcome');
});

Route::get('test', function () {
    event(new App\Events\NodeRecordEvent(true));
    return response()->json(['message'=>'Event sended']);
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
