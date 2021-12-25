<?php

use App\Models\Course;
use App\Models\TestAnswer;
use App\Models\TestQuestion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

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

Route::get('cache_clear', function () {
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    dd("cache:clear");
});
Route::get('config_clear', function () {
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    dd("config:clear");
});
Route::get('storage', function () {
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    dd("storage");
});

Route::get('migrate', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate');
        dd("Success to migrate");
    } catch (Exception $exception){
        throw $exception;
    }
});

Route::get('/', function () {
    return view('welcome',[

    ]);
});
Route::get('/clean_video', 'CleanController@clean_video');
Route::get('/clean_pdf', 'CleanController@clean_pdf');
Route::get('/clean_course_img', 'CleanController@clean_course_img');
