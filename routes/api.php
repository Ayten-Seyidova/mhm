<?php

use Illuminate\Support\Facades\Route;

use \App\Http\Controllers\Api\AuthController;
use \App\Http\Controllers\Api\NotificationsController;
use \App\Http\Controllers\Api\SettingsController;
use \App\Http\Controllers\Api\CommentsController;
use \App\Http\Controllers\Api\ResultController;
use \App\Http\Controllers\Api\ResultGuestController;
use \App\Http\Controllers\Api\VideoController;
use \App\Http\Controllers\Api\OtpController;
use \App\Http\Controllers\Api\GuestController;
use \App\Http\Controllers\Api\AboutController;
use \App\Http\Controllers\Api\NotificationsGuestController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/registerRequest', [AuthController::class, 'registerRequest'])->name('api.registerRequest');
Route::get('/sliders', [SettingsController::class, 'sliders'])->name('sliders');
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
Route::get('/faq', [SettingsController::class, 'faq'])->name('faq');

Route::group(['middleware' => ['auth:api']], function () {

    //auth
    Route::get('/userDetails', [AuthController::class, 'userDetails'])->name('userDetails');
    Route::post('/uploadImage', [AuthController::class, 'uploadImage'])->name('api.uploadImage');
    Route::put('/updateUserData', [AuthController::class, 'updateUserData'])->name('api.updateUserData');

    //course
    Route::post('/videoCourses/{type}', [VideoController::class, 'videoCourses'])->name('videoCourses');
    Route::post('/myVideoCourses/{type}', [VideoController::class, 'myVideoCourses'])->name('myVideoCourses');
    Route::post('/setVideoDone', [VideoController::class, 'setVideoDone'])->name('setVideoDone');
    Route::post('/exam', [VideoController::class, 'exam'])->name('exam');
    Route::get('/videos', [VideoController::class, 'videos'])->name('videos');

    //groups
    Route::get('/groups', [SettingsController::class, 'groups'])->name('groups');

    //comments
    Route::post('/setComment', [CommentsController::class, 'setComment'])->name('setComment');

    //result
    Route::post('/setResult', [ResultController::class, 'setResult'])->name('setResult');
    Route::get('/getResult', [ResultController::class, 'getResult'])->name('getResult');

    //notifications
    Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications');
    Route::put('/notification', [NotificationsController::class, 'update'])->name('notification-update');
    Route::delete('/notification', [NotificationsController::class, 'delete'])->name('notification-delete');
    Route::post('/setParam', [NotificationsController::class, 'setParam'])->name('setParam');
    Route::delete('/deleteParam', [NotificationsController::class, 'deleteParam'])->name('deleteParam');

});



Route::match(['post','put'],'/sendOtp', [OtpController::class, 'sendOtp'])->name('sendOtp');
Route::post('/checkOtpRegister', [OtpController::class, 'checkOtpRegister'])->name('checkOtpRegister');
Route::put('/checkOtpLogin', [OtpController::class, 'checkOtpLogin'])->name('checkOtpLogin');
Route::get('/directions', [GuestController::class, 'directions'])->name('directions');

Route::group(['middleware' => ['auth:apiGuest']], function () {
    //auth
    Route::get('/guestDetails', [AuthController::class, 'guestDetails'])->name('guestDetails');
    Route::put('/updateGuestData', [AuthController::class, 'updateGuestData'])->name('api.updateGuestData');
    Route::post('/uploadImageGuest', [AuthController::class, 'uploadImage'])->name('api.uploadImageGuest');

    //posts
    Route::get('/posts', [GuestController::class, 'posts'])->name('posts');
    Route::post('/answer', [GuestController::class, 'answer'])->name('answer');

    //guestExams
    Route::get('/guestExam', [GuestController::class, 'guestExam'])->name('guestExam');

    Route::get('/stories', [GuestController::class, 'stories'])->name('stories');
    Route::get('/teachers', [GuestController::class, 'teachers'])->name('teachers');
    Route::get('/about', [AboutController::class, 'index'])->name('about');
    Route::get('/ourTeachers', [GuestController::class, 'ourTeachers'])->name('ourTeachers');
    Route::get('/lessons', [GuestController::class, 'lessons'])->name('lessons');
    Route::get('/books', [GuestController::class, 'books'])->name('books');
    Route::post('/sendCommentByPost', [GuestController::class, 'sendCommentByPost'])->name('sendCommentByPost');
    Route::get('/getCommentsByPost', [GuestController::class, 'getCommentsByPost'])->name('getCommentsByPost');
    Route::delete('/deleteComment', [GuestController::class, 'deleteComment'])->name('deleteComment');
    Route::post('/setLikeByPost', [GuestController::class, 'setLikeByPost'])->name('setLikeByPost');

    //notificationsGuest
    Route::get('/notificationsGuest', [NotificationsGuestController::class, 'index'])->name('notifications-guest');
    Route::put('/notificationGuest', [NotificationsGuestController::class, 'update'])->name('notification-guest-update');
    Route::delete('/notificationGuest', [NotificationsGuestController::class, 'delete'])->name('notification-guest-delete');
    Route::post('/setParamGuest', [NotificationsGuestController::class, 'setParam'])->name('setParam-guest');
    Route::delete('/deleteParamGuest', [NotificationsGuestController::class, 'deleteParam'])->name('deleteParam-guest');

    Route::post('/setResultGuest', [ResultGuestController::class, 'setResultGuest'])->name('setResultGuest');
    Route::get('/getResultGuest', [ResultGuestController::class, 'getResultGuest'])->name('getResultGuest');
});
