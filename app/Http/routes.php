<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/




/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['api']], function () {


    Auth::loginUsingId(1);

    Route::resource('upload', 'UploadController');

});


Route::group(['middleware' => ['web']], function () {
    //

    Auth::loginUsingId(1);

    Route::any('/', function () {
        return view('welcome');
    });


    /**
     * POS Sim
     *
     */
    Route::get('pos/send', 'POSController@send');
    Route::get('pos/check', 'POSController@check');

    Route::get('pos', 'POSController@index');

    /**
     * Upload Functions
     *
     */
    Route::get('link', 'UploadController@getLink');
    Route::controller('upload', 'UploadController');

    /**
     * Search function
     */

    Route::controller('search', 'SearchController');


    /**
     * Budget feature
     */

    Route::controller('budget', 'BudgetController');



// Authentication Routes...
    Route::auth();

});
