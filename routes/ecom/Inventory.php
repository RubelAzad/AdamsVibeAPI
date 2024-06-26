<?php

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

Route::group(['prefix' => 'inventories'], function () {
    Route::get('/', 'InventoryController@index');
    Route::get('/discounted', 'InventoryController@discounted');
    Route::get('/categories/{categoryId}', 'InventoryController@byCategory');
    Route::get('/subCategories/{subCategoryId}', 'InventoryController@bySubCategory');
    Route::get('/{id}/show', 'InventoryController@show');
    Route::get('/search', 'InventoryController@search');
    Route::post('/{inventoryId}/inventory-variants', 'InventoryController@storeInventoryVariants');
    Route::get('/products/{productId}/variations/options', 'InventoryController@getProductVariationOptions');
    
    
    Route::get('/preOrder', 'InventoryController@PreOrder');
    Route::get('/preDiscounted', 'InventoryController@PreDiscounted');
    Route::get('/{id}/showPreOrder', 'InventoryController@ShowPreOrder');

    Route::get('/vendorPro', 'InventoryController@VendorPro');
    Route::get('/vendorProDiscounted', 'InventoryController@VendorProDiscounted');
    Route::get('/{id}/showVendorProduct', 'InventoryController@ShowVendorProduct');

    Route::get('/bulk-product', 'InventoryController@bulkProduct');
});
