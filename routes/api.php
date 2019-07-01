<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('login','Api\UserController@login');
Route::post('register','Api\UserController@store');

Route::get('node-record/store', 'Api\LineRecord@eventData');
Route::get('node-record/notif', 'Api\LineRecord@notification');

Route::get('node-record/test', 'Api\LineRecord@selectTest');




Route::group(['middleware' => 'auth:api'], function () {
    Route::get('details', 'Api\UserController@details');


    //Leakage List
    Route::get('leakage/{lineId}/','Api\LineLeakage@index');
    Route::get('node-master/{id}','Api\LineLeakage@show');

    //Node Master
    Route::get('node-master/','Api\NodeMasterController@getAll');
    Route::get('node-master/{id}','Api\NodeMasterController@show');

    Route::post('node-master/store','Api\NodeMasterController@store');
    Route::post('node-master/update/{id}','Api\NodeMasterController@update');
    Route::post('node-master/destroy/{id}','Api\NodeMasterController@destroy');


    //Line Master
    Route::get('line-master/lines', 'Api\LineMasterController@lineList');
    Route::get('line-master/{id}', 'Api\LineMasterController@lineListById');

    Route::post('line-master/store', 'Api\LineMasterController@store');
    Route::post('line-master/update/{id}', 'Api\LineMasterController@update');
    Route::post('line-master/destroy/{id}', 'Api\LineMasterController@destroy');


    //Line History
    // Route::get('line-record/history/{hour}','Api\LineRecord@history');
    Route::get('line-record/latest/{lineId}','Api\LineRecord@latest');


    Route::get('line-history/{lineId}','Api\LineRecord@lineHistory');
    Route::get('line-history/hour/{lineId}','Api\LineRecord@lineHourlyRecord');





    Route::get('line-recap/{lineId}','Api\LineRecord@lineHourlyRecap');

});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
