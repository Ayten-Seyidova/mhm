<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\TeacherGroupController;
use App\Http\Controllers\Admin\RegisterController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\VideoCourseController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\ActionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/payment-notification', [CustomerController::class, 'payment']);

Route::prefix('administrator/mhm')->middleware('is_admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/editor/upload', [AdminController::class, 'upload'])->name('editor.upload');
    Route::resource('/user', UserController::class);
    Route::post('user/changeStatus', [UserController::class, 'changeStatus'])->name('user.changeStatus');
    Route::post('user/checked', [UserController::class, 'checked'])->name('user.checked');
    Route::resource('/group', GroupController::class);
    Route::post('group/checked', [GroupController::class, 'checked'])->name('group.checked');
    Route::resource('/register', RegisterController::class);
    Route::post('register/checked', [RegisterController::class, 'checked'])->name('register.checked');
    Route::resource('/action', ActionController::class);
    Route::resource('/customer', CustomerController::class);
    Route::post('customer/changeStatus', [CustomerController::class, 'changeStatus'])->name('customer.changeStatus');
    Route::post('customer/checked', [CustomerController::class, 'checked'])->name('customer.checked');
    Route::post('customer/device', [CustomerController::class, 'device'])->name('customer.device');
    Route::resource('/slider', SliderController::class);
    Route::post('slider/checked', [SliderController::class, 'checked'])->name('slider.checked');
    Route::resource('/faq', FaqController::class);
    Route::post('faq/checked', [FaqController::class, 'checked'])->name('faq.checked');
    Route::resource('/settings', SettingController::class);
    Route::get('/password', [SettingController::class, 'password'])->name('password');
    Route::post('/change-password', [SettingController::class, 'changePassword'])->name('changePassword');
});

Route::prefix('administrator/mhm')->middleware('is_teacher')->group(function () {
    Route::resource('/teacher', TeacherGroupController::class);
});

Route::prefix('administrator/mhm')->middleware('is_admin_or_teacher')->group(function () {
    Route::resource('/video-course', VideoCourseController::class);
    Route::post('video-course/changeStatus', [VideoCourseController::class, 'changeStatus'])->name('video-course.changeStatus');
    Route::post('video-course/checked', [VideoCourseController::class, 'checked'])->name('video-course.checked');
    Route::resource('/subject', SubjectController::class);
    Route::post('subject/changeStatus', [SubjectController::class, 'changeStatus'])->name('subject.changeStatus');
    Route::post('subject/checked', [SubjectController::class, 'checked'])->name('subject.checked');
    Route::resource('/video', VideoController::class);
    Route::post('video/checked', [VideoController::class, 'checked'])->name('video.checked');
    Route::resource('/exam', ExamController::class);
    Route::post('exam/changeStatus', [ExamController::class, 'changeStatus'])->name('exam.changeStatus');
    Route::post('exam/checked', [ExamController::class, 'checked'])->name('exam.checked');
    Route::resource('/question', QuestionController::class);
    Route::post('question/changeStatus', [QuestionController::class, 'changeStatus'])->name('question.changeStatus');
    Route::post('question/checked', [QuestionController::class, 'checked'])->name('question.checked');
    Route::resource('/result', ResultController::class);
});

Route::group(['prefix' => 'administrator/mhm'], function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::get('optimize', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('optimize');
    return 'Successfully cleared';
});

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Successfully storage linked!';
});
