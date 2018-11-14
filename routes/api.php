<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::get('/languages', 'LanguagesController@index');
Route::get('/language/{code}', 'LanguagesController@fetch');
Route::get('/defaults', 'LanguagesController@defaults');
Route::post('/store', 'LanguageStoreController@handle');
Route::delete('/delete/{code}', 'LanguageDeleteController@handle');
Route::post('/update/{code}', 'LanguageUpdateController@handle');
Route::post('/language-fields/fetch', 'LanguageFieldsFetchController@handle');
