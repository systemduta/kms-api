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

Route::group([
    'namespace' => 'Api',
],function(){
    Route::post('login', 'UserController@login');
    Route::post('login_mobile', 'MobileController@login_mobile');

    Route::group([
        'prefix' => 'web'
    ],function(){
        Route::post('register', 'UserController@register');
        Route::get('get_company', 'CompanyController@index');
        Route::get('get_organization', 'OrganizationController@index');
        // Route::resource('books','BookController');

        Route::group(['middleware' => 'auth:api'], function(){
            Route::get('get_user', 'UserController@index');
            Route::get('detail_admin', 'UserController@details');
            Route::get('detail_user/{id}', 'UserController@detailsUser');
            Route::put('update_user/{id}', 'UserController@update');
            Route::delete('delete_user/{id}', 'UserController@delete');
            Route::post('logout', 'UserController@logout');
//            Route::get('organization/list_by_company', 'OrganizationController@get_organization_by_company');
            Route::resource('organization','OrganizationController');
            Route::resource('companies','CompanyController');
            Route::get('detail_event/{id}', 'EventController@detailsEvent');
            Route::get('detail_course/{id}', 'CourseController@detailsCourse');
            Route::get('get_question/{id}', 'TestController@index');

            Route::post('store_question', 'TestController@store');

            Route::post('store_cross', 'CrossfunctionController@store');
            Route::get('getcross/{id}', 'CrossfunctionController@index');
            Route::delete('delete_cross/{id}', 'CrossfunctionController@destroy');

            Route::delete('delete_question/{id}', 'TestQuestionController@destroy');
            Route::get('detail_question/{id}', 'TestQuestionController@show');
            Route::put('update_question/{id}', 'TestQuestionController@update');

            Route::delete('delete_answer/{id}', 'TestAnswerController@destroy');
            Route::put('update_answer/{id}', 'TestAnswerController@update');
            Route::get('detail_answer/{id}', 'TestAnswerController@show');

            Route::get('get_book', 'BookController@index');
            Route::get('detail_book/{id}', 'BookController@show');
            Route::post('create', 'BookController@store');
            Route::put('update_book/{id}', 'BookController@update');
            Route::delete('delete_book/{id}', 'BookController@destroy');

            // Route::get('get_sop', 'Api/SOPController@index');
            // Route::post('create_sop', 'SOPController@store');
            Route::resource('sop','SOPController');
            Route::get('get_sop','SOPController@sop');
            Route::get('sop_status/{id}','SOPController@status');

            Route::resource('lampiran','LampiranController');
            Route::get('lamp_status/{id}','LampiranController@status');

            Route::resource('crossfunction','CrossfunctionController');
            Route::get('cross_status/{id}' ,'CrossfunctionController@status');
            Route::get('get_cross' ,'CrossfunctionController@cross');
            Route::resource('cros_lamp','LamcrossController');
            Route::get('lamcross_status/{id}','LamcrossController@status');


            Route::get('golongan/list_by_company','GolonganController@get_golongan_by_company');
            Route::resource('golongan','GolonganController');
            Route::resource('course','CourseController');
            Route::get('leaderboard/exam_result','LeaderboardController@exam_result');
            Route::resource('leaderboard','LeaderboardController');
            Route::resource('event','EventController');
            Route::resource('test','TestController');
            Route::resource('vhs','VhsController');
            Route::resource('splash_screen','SplashScreenController');
        });
    });

    Route::group([
        'prefix' => 'mobile'
    ],function(){
        Route::resource('books','BookController');
        Route::get('splash_screen','SplashScreenController@index');

        Route::group(['middleware' => 'auth:api'], function(){
            Route::post('firebase_token','MobileController@firebase_token');
            Route::get('user_course','MobileController@user_course');
            Route::get('detail_user', 'MobileController@details');
            Route::post('logout', 'MobileController@logout');
//            Route::get('course_list','MobileController@course_list'); Sementara ndak di pakai
            Route::get('course_list_dashboard','MobileController@course_list_dashboard');

            Route::post('accept_sop','MobileController@accept_sop');
            Route::get('sop_detail','MobileController@sop_detail');
            Route::get('sop_list','MobileController@sop_list');
            Route::get('sop_download/{id}','MobileController@downFileSop');
            Route::get('lampiran_download/{id}','MobileController@downFileLampiran');
            Route::get('lampiran','MobileController@lampiran');

            Route::get('cross_download/{id}','MobileController@downFileCross');
            Route::get('lamcross_download/{id}','MobileController@downFileLamCross');
            Route::get('cross_list','MobileController@cross_list');
            Route::get('cross_detail/{id}','MobileController@cross_detail');
            Route::get('lamcross','MobileController@lamcross');

            Route::get('leaderboards','MobileController@leaderboards');
            Route::get('calendar','MobileController@get_calendar');
            Route::post('calendar','MobileController@post_calendar');
            Route::get('course_detail/{id}','MobileController@course_detail');
            Route::post('accept_course','MobileController@accept_course');
            Route::post('submit_question','MobileController@submit_question');
            Route::post('submit_answer','MobileController@submit_answer');
            Route::post('change_password', 'MobileController@change_password');
            Route::get('vhs','MobileController@list_vhs');
            Route::get('vhs_dashboard','VhsController@index');
        });
    });

});
