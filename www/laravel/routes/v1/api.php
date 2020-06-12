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
$apiResources = [
    'users' => 'UserController',
    'news' => 'NewsController',
    'roles' => 'RoleController',
    'specializations' => 'SpecializationController',
    'relations' => 'RelationController',
    'classes' => 'ClassController',
    'notifications' => 'NotificationController',
];

Route::group(['prefix' => 'v1', 'middleware' => ['cors']], function() use($apiResources) {
    Route::group(['prefix' => 'signin'], function () {
        Route::post('/login', 'SignController@signinByLogin');
    });

    Route::middleware(['auth:api'])->group(function() use($apiResources){
        Route::apiResources($apiResources);

        Route::post( '/notifications/readAll', 'NotificationController@readAll');

        Route::match(['GET', 'POST'], '/signout', 'SignController@signout');

        Route::group(['prefix' => 'media'], function () {
            Route::delete('delete/{id}', 'MediaController@delete');
        });

        Route::prefix('user')->group(function () {
            Route::post('me', "UserController@me");

            Route::prefix('id{user_id}')->group(function () {
                //Профиль пользователя
                Route::match(['GET', 'POST'], '/', 'UserProfileController@show');
            });
        });
    });
});