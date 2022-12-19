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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::namespace("App\Http\Controllers")->group(function() {
    Route::post("company", "CompanyController@store")->name("api.company.store");
    Route::get("company/{company}", "CompanyController@edit")->name("api.company.edit");
    Route::put("company/{company}", "CompanyController@update")->name("api.company.update");
    Route::delete("company/{company}", "CompanyController@destroy")->name("api.company.destroy");
});
