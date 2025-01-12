<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use \App\Http\Controllers\Api\AuthController;
use \App\Http\Controllers\Api\NotificationsController;
use \App\Http\Controllers\Api\SettingsController;
use \App\Http\Controllers\Api\CommentsController;
use \App\Http\Controllers\Api\ResultController;
use \App\Http\Controllers\Api\VideoController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/registerRequest', [AuthController::class, 'registerRequest'])->name('api.registerRequest');


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


    //settings
    Route::get('/sliders', [SettingsController::class, 'sliders'])->name('sliders');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::get('/faq', [SettingsController::class, 'faq'])->name('faq');
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
