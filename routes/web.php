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

Auth::routes();

Route::get('/',function(){
    return Redirect::to('/home/goodsIn');
});
Route::get('/admin', function () {
    return Redirect::to('/admin/storehouseSetting');
});
Route::get('/home/', 'HomeController@goodsIn');
Route::get('/home/goodsIn', 'HomeController@goodsIn');
Route::put('/home/goodsInSave', 'HomeController@goodsInSave');
Route::put('/home/generateDealNumber/{brandId}/{num}/{dealNumber}', 'HomeController@generateDealNumber');
Route::get('/home/goodsOut', 'HomeController@goodsOut');
Route::put('/home/goodsOutSave', 'HomeController@goodsOutSave');
Route::put('/home/isExistClient/{clientName}', 'HomeController@isExistClient');
Route::put('/home/addClient', 'HomeController@addClient');
Route::put('/home/cancelOut/{record_id}', 'HomeController@cancelOut');
Route::put('/home/cancelIn/{record_id}', 'HomeController@cancelIn');
Route::get('/home/getGoodsInfo/{dealNumber}', 'HomeController@getGoodsInfo');
Route::get('/home/goodsManage', 'HomeController@goodsManage');
Route::get('/home/inOutRecords', 'HomeController@inOutRecords');
Route::put('/home/alterContract', 'HomeController@alterContract');
Route::put('/home/alterPurchaseType', 'HomeController@alterPurchaseType');
Route::put('/home/alterExpressNo', 'HomeController@alterExpressNo');
Route::put('/home/changePassword', 'HomeController@changePassword');
Route::put('/home/generateDealNumberTxt', 'HomeController@generateDealNumberTxt');
Route::get('/home/downloadDealNumberTxt', 'HomeController@downloadDealNumberTxt');



