
<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('index');
});

Route::group(['prefix' => 'api'], function()
{

    Route::post('register', 'AuthenticateController@register');
    Route::post('authenticate', 'AuthenticateController@authenticate');
    Route::get('/authenticate/user', 'AuthenticateController@getAuthUser');

    Route::post('event/save', 'EventController@store');
    Route::get('event', 'EventController@index');
    Route::get('event/{id}', 'EventController@edit');
    Route::post('event/update/{id}', 'EventController@update');
    Route::get('event/delete/{id}', 'EventController@destroy');
    Route::get('event/search/{searchEvent}', 'EventController@search');

    Route::get('user', 'UserController@index');
    Route::get('user/{id}', 'UserController@edit');
    Route::post('user/update/{id}', 'UserController@update');
});
