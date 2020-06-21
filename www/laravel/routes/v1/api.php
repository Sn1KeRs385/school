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
    'messages' => 'MessageController',
    'schedule_types' => 'ScheduleTypeController',
    'class_semesters' => 'ClassSemesterController',
    'class_lessons' => 'ClassLessonController',
];

Route::group(['prefix' => 'v1', 'middleware' => ['cors']], function() use($apiResources) {
    Route::group(['prefix' => 'signin'], function () {
        Route::post('/login', 'SignController@signinByLogin');
    });

    Route::middleware(['auth:api'])->group(function() use($apiResources){

        Route::post( '/notifications/readAll', 'NotificationController@readAll');

        Route::match(['GET', 'POST'], '/signout', 'SignController@signout');

        Route::group(['prefix' => 'messages'], function () {
            Route::get('/unreadCounter', 'MessageController@unreadCounter');
            Route::get('/getChats', 'MessageController@getChats');
            Route::get('/getMessages', 'MessageController@getMessages');
            Route::get('/sendMessage', 'MessageController@sendMessage');
        });

        Route::group(['prefix' => 'classes'], function () {
            Route::get('/getMembers', 'ClassController@getMembers');
            Route::post('/setMembers', 'ClassController@setMembers');
            Route::get('/getStudentsWithProgress', 'ClassController@getStudentsWithProgress');
            Route::post('/saveStudentsProgress', 'ClassController@saveStudentsProgress');
            Route::get('/getReport', 'ClassController@getReport');
            Route::get('/getClassReport', 'ClassController@getClassReport');
        });

        Route::group(['prefix' => 'users'], function () {
            Route::get('/getSchedule', 'UserController@getSchedule');
        });

        Route::prefix('user')->group(function () {
            Route::post('me', "UserController@me");

            Route::prefix('id{user_id}')->group(function () {
                //Профиль пользователя
                Route::match(['GET', 'POST'], '/', 'UserProfileController@show');
            });
        });

        Route::apiResources($apiResources);
    });
});