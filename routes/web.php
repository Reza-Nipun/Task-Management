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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/users', 'UserController@users')->name('users');
Route::get('/edit_user/{id}', 'UserController@editUser')->name('edit_user');

Route::get('/tasks', 'TaskController@getTasks')->name('tasks');
Route::get('/get_assigned_task_detail', 'TaskController@getAssignedTaskDetail')->name('get_assigned_task_detail');
Route::get('/edit_task/{id}', 'TaskController@editTask')->name('edit_task');
Route::post('/update_task/{id}', 'TaskController@updateTask')->name('update_task');
Route::get('/my_tasks', 'TaskController@myTasks')->name('my_tasks');
Route::get('/add_task', 'TaskController@addTask')->name('add_task');
Route::post('/save_task', 'TaskController@saveTask')->name('save_task');
Route::get('/upload_task_file', 'TaskController@uploadTaskFile')->name('upload_task_file');
Route::post('/uploading_task_file', 'TaskController@uploadingTaskFile')->name('uploading_task_file');
Route::post('/complete_assigned_task', 'TaskController@completeAssignedTask')->name('complete_assigned_task');
Route::post('/terminate_assigned_task', 'TaskController@terminateAssignedTask')->name('terminate_assigned_task');
Route::post('/reschedule_task_delivery_date', 'TaskController@rescheduleTaskDeliveryDate')->name('reschedule_task_delivery_date');
Route::get('/assigned_tasks_report', 'TaskController@assignedTasksReport')->name('assigned_tasks_report');
Route::post('/get_assigned_task_report', 'TaskController@getAssignedTaskReport')->name('get_assigned_task_report');
Route::get('/my_tasks_report', 'TaskController@myTasksReport')->name('my_tasks_report');
Route::post('/get_my_task_report', 'TaskController@getMyTaskReport')->name('get_my_task_report');
