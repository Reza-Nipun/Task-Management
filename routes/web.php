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

//Auth::routes();
//
//Route::get('/home', 'HomeController@index')->name('home');
//
//Auth::routes();
//
//Route::get('/home', 'HomeController@index')->name('home');
//
//Auth::routes();
//
//Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/users', 'UserController@users')->name('users');
Route::get('/edit_user/{id}', 'UserController@editUser')->name('edit_user');
Route::post('/update_user/{id}', 'UserController@updateUser')->name('update_user');

Route::get('/tasks', 'TaskController@getTasks')->name('tasks');
Route::get('/get_assigned_task_detail', 'TaskController@getAssignedTaskDetail')->name('get_assigned_task_detail');
Route::get('/get_sub_task_detail', 'TaskController@getSubTaskDetail')->name('get_sub_task_detail');
Route::post('/sub_task_status_change', 'TaskController@subTaskStatusChange')->name('sub_task_status_change');
Route::get('/edit_task/{id}', 'TaskController@editTask')->name('edit_task');
Route::post('/update_task/{id}', 'TaskController@updateTask')->name('update_task');
Route::post('/update_task_info', 'TaskController@updateTaskInfo')->name('update_task_info');
Route::post('/save_sub_task', 'TaskController@saveSubTask')->name('save_sub_task');
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
Route::post('/get_assigned_pending_task_filter', 'TaskController@getAssignedPendingTaskFilter')->name('get_assigned_pending_task_filter');
Route::post('/get_pending_task_filter', 'TaskController@getPendingTaskFilter')->name('get_pending_task_filter');
Route::get('/my_tasks_report', 'TaskController@myTasksReport')->name('my_tasks_report');
Route::post('/get_my_task_report', 'TaskController@getMyTaskReport')->name('get_my_task_report');
Route::get('/task_confirmation/{task_id}', 'TaskController@taskConfirmation')->name('task_confirmation');
Route::get('/performance_report', 'TaskController@performanceReport')->name('performance_report');
Route::post('/get_performance_report', 'TaskController@getPerformanceReport')->name('get_performance_report');
Route::get('/get_email_wise_task_list/{email?}/{from_assign_date?}/{to_assign_date?}', 'TaskController@getEmailWiseTaskList')->name('get_email_wise_task_list');
Route::get('/get_email_wise_pending_task_list/{email?}/{from_assign_date?}/{to_assign_date?}', 'TaskController@getEmailWisePendingTaskList')->name('get_email_wise_pending_task_list');
Route::get('/get_email_wise_terminate_task_list/{email?}/{from_assign_date?}/{to_assign_date?}', 'TaskController@getEmailWiseTerminatedTaskList')->name('get_email_wise_terminate_task_list');

Route::get('/meetings', 'MeetingController@index')->name('meetings');
Route::get('/check_pending_meeting/{task_id}', 'MeetingController@checkPendingMeeting')->name('check_pending_meeting');
Route::post('/fix_meeting_date_time', 'MeetingController@store')->name('fix_meeting_date_time');
Route::get('/edit_meeting/{id}', 'MeetingController@editMeeting')->name('edit_meeting');
Route::post('/update_meeting/{id}', 'MeetingController@updateMeeting')->name('update_meeting');
Route::post('/meeting_complete', 'MeetingController@meetingComplete')->name('meeting_complete');
Route::get('/schedule_task_completion_meeting/{task_id}', 'MeetingController@scheduleTaskCompletionMeeting')->name('schedule_task_completion_meeting');
Route::post('/fix_schedule_task_completion_meeting', 'MeetingController@fixScheduleTaskCompletionMeeting')->name('fix_schedule_task_completion_meeting');

Route::get('/reschedule_task_from_email/{task_id}', 'AccessFromEmail@rescheduleTaskFromEmail')->name('reschedule_task_from_email');
Route::post('/rescheduling_task/{task_id}', 'AccessFromEmail@rescheduleTaskDeliveryDate')->name('rescheduling_task');
Route::get('/rescheduling_meeting_from_email/{meeting_id}', 'AccessFromEmail@reschedulingMeetingFromEmail')->name('rescheduling_meeting_from_email');
Route::post('/rescheduling_meeting/{meeting_id}', 'AccessFromEmail@reschedulingMeeting')->name('rescheduling_meeting');

// Auto-Mail Notifications
Route::get('/auto_mail_delivery_date_tasks_notification', 'AccessFromEmail@autoMailDeliveryDateTasksNotification')->name('auto_mail_delivery_date_tasks_notification');
Route::get('/auto_mail_halfway_delivery_date_tasks_notification', 'AccessFromEmail@autoMailHalfwayDeliveryDateTasksNotification')->name('auto_mail_halfway_delivery_date_tasks_notification');
Route::get('/auto_mail_meeting_notification', 'AccessFromEmail@autoMailMeetingNotification')->name('auto_mail_meeting_notification');
Route::get('/test_mail', 'AccessFromEmail@testMail')->name('test_mail');