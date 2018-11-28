<?php

use App\Admin\Controllers\UserController;
use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'StorehouseSettingController@index');
    $router->resource('users', UserController::class);
    $router->get('/storehouseSetting/', 'StorehouseSettingController@index');
    $router->get('/storehouseSetting/create', 'StorehouseSettingController@create');
    $router->get('/storehouseSetting/edit', 'StorehouseSettingController@edit');
    $router->any('/storehouseSetting/store', 'StorehouseSettingController@store');
    $router->get('/storehouseSetting/userEdit', 'StorehouseSettingController@userEdit');
    $router->any('/storehouseSetting/userSave', 'StorehouseSettingController@userSave');
    $router->post('/storehouseSetting/isFreeze', 'StorehouseSettingController@isFreeze');
    $router->post('/storehouseSetting/resetPassword', 'StorehouseSettingController@resetPassword');
    $router->get('/storehouseSetting/addUserToStoreHouse', 'StorehouseSettingController@addUserToStoreHouse');
    $router->post('/storehouseSetting/addUserToStoreHouseSave', 'StorehouseSettingController@addUserToStoreHouseSave');
    $router->post('/storehouseSetting/destroy', 'StorehouseSettingController@destroy');
    $router->get('/storehouseSetting/createUser', 'StorehouseSettingController@createUser');
    $router->post('/storehouseSetting/createUserSave', 'StorehouseSettingController@createUserSave');

    $router->get('/clientSetting/', 'ClientSettingController@index');
    $router->get('/clientSetting/{id}/destroy', 'ClientSettingController@destroy');
    $router->get('/clientSetting/create', 'ClientSettingController@create');
    $router->post('/clientSetting/createSave', 'ClientSettingController@createSave');
    $router->get('/clientSetting/{id}/edit', 'ClientSettingController@edit');
    $router->any('/clientSetting/{id}/editSave', 'ClientSettingController@editSave');
    $router->get('/clientSetting/{id}', function($id) {
        if (is_numeric($id)) {
            return Redirect::to("/admin/clientSetting");
        } else {
            return Redirect::to('/admin/clientSetting/' . $id);
        }
    });
    $router->delete('/clientSetting/{id}', 'ClientSettingController@destroy');

    $router->get('/companySetting/', 'CompanySettingController@index');
    $router->get('/companySetting/addSeller', 'CompanySettingController@addSeller');
    $router->post('/companySetting/addSellerSave', 'CompanySettingController@addSellerSave');
    $router->get('/companySetting/createSeller', 'CompanySettingController@createSeller');
    $router->post('/companySetting/createSellerSave', 'CompanySettingController@createSellerSave');
    $router->get('/companySetting/editSeller', 'CompanySettingController@editSeller');
    $router->post('/companySetting/editSellerSave', 'CompanySettingController@editSellerSave');
    $router->delete('/companySetting/sellerDestroy', 'CompanySettingController@sellerDestroy');
    $router->get('/companySetting/companyCreate', 'CompanySettingController@companyCreate');
    $router->post('/companySetting/companyCreateSave', 'CompanySettingController@companyCreateSave');
    $router->get('/companySetting/{companyId}/{companyName}/companyEdit', 'CompanySettingController@companyEdit');
    $router->post('/companySetting/companyEditSave', 'CompanySettingController@companyEditSave');
    $router->delete('/companySetting/{companyId}/companyDestroy', 'CompanySettingController@companyDestroy');

    $router->get('/purchaseTypeSetting/', 'PurchaseTypeSettingController@index');
    $router->get('/purchaseTypeSetting/create', 'PurchaseTypeSettingController@create');
    $router->post('/purchaseTypeSetting/createSave', 'PurchaseTypeSettingController@createSave');
    $router->get('/purchaseTypeSetting/{purchaseTypeId}/edit', 'PurchaseTypeSettingController@edit');
    $router->post('/purchaseTypeSetting/editSave', 'PurchaseTypeSettingController@editSave');
    $router->delete('/purchaseTypeSetting/{purchaseTypeId}/destroy', 'PurchaseTypeSettingController@destroy');
    $router->get('/PurchaseTypeSettingController/{id}', function($id) {
        if (is_numeric($id)) {
            return Redirect::to("/admin/purchaseTypeSetting");
        } else {
            return Redirect::to('/admin/purchaseTypeSetting/' . $id);
        }
    });

    $router->get('/frontUser/', 'FrontUserController@index');
    $router->get('/frontUser/changePassword', 'FrontUserController@changePassword');

});
