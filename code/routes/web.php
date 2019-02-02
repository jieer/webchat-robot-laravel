<?php

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

Route::get('/', 'RobotController@index');
Route::post('/cookies', 'RobotController@cookies');
Route::get('/status', 'RobotController@status');
Route::get('/chat', 'RobotController@chat');
Route::post('/init', 'RobotController@init');
Route::post('/users', 'RobotController@users');
Route::post('/send', 'RobotController@send');
Route::post('/sync', 'RobotController@sync');
Route::get('/avatar', 'RobotController@avatar');
Route::post('/tuling', 'RobotController@tuling');
