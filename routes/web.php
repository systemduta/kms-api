<?php

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

Route::get('/', function () {
    return view('welcome');
});
//Route::get('/clean_video', 'CleanController@clean_video');
//Route::get('/clean_pdf', 'CleanController@clean_pdf');
//Route::get('/clean_course_img', 'CleanController@clean_course_img');
