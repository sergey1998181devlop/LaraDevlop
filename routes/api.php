<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/news', function (Request $request) {
//    Route::get('/', function (){
//        echo 23424;
//        die();
//    });
//});

//Route::post('/news' , function (){
//    return 34;
//    Route::resource('/news', 'NewsController@execute');
//    Route::get('/list', 'NewsController@execute');
//    Route::resource('products', 'API\ProductController');
//});
Route::apiResource('/news', 'App\Http\Controllers\API\NewsController');
//Route::group(['prefix'=>'news'], function() {
//    return 34;
//    Route::get('/news', 'NewsController@index');
//
//});
//Route::post('api/vk/callback', ['uses'=>'NewsController@execute']);
