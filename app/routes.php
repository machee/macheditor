<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

App::bind('ProjectRepository', 'FsProjectRepository');

Route::get('/',                'Main@projects');
Route::get('{project}',        'Main@project');
Route::get('{project}/{file}', 'Main@edit')->where('file', '.+');

// RESTful Files
Route::put(   'api/{project}/{file}', 'Api@update')->where('file', '.+');
Route::delete('api/{project}/{file}', 'Api@delete')->where('file', '.+');

