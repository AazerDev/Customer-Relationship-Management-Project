<?php

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\LoginController;
// use App\Http\Controllers\DashboardController;
// use App\Http\Controllers\UsersController;
// use Illuminate\Support\Facades\Redis;
// use App\Jobs\TestRedisQueueJob;


// Route::get('/redis-test', function () {
//     Redis::set('test-key', 'Redis is working!');
//     return Redis::get('test-key');
// });

// Route::get('/set-session', function () {
//     session(['redis_session' => 'Redis session working!']);
//     return 'Session stored.';
// });

// Route::get('/get-session', function () {
//     return session('redis_session', 'No session found.');
// });

// Route::get('/test-queue', function () {
//     TestRedisQueueJob::dispatch();
//     return 'Job dispatched!';
// });

// // Login and forgot password routes
// Route::controller(LoginController::class)->group(function(){
//     Route::get('/','index')->name('login')->middleware('guest');
//     Route::post('login/check','login')->name('loggedin');
//     Route::get('/logout',  'logout')->name('logout');
//     Route::get('/forgot-password', 'forgotpassword')->name('forgotpassword');
//     Route::post('/create-forget-password-token',  'createForgetPasswordToken')->name('createForgetPasswordToken');
//     Route::get('validation/{hash}/{email}', 'CheckHashPassword')->name('password-validation');
//     Route::post('update-password', 'updatePassword')->name('update.password');
// });
// Route::get('/forgot-password/enter-code', [LoginController::class, 'enterCode'])->name('enterCode');
// Route::get('/forgot-password/success', [LoginController::class, 'forgotpasswordSuccess'])->name('forgotpasswordSuccess');

// // Dashboard route
// Route::controller(DashboardController::class)->middleware('auth')->group(function(){
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// });

// Route::controller(UsersController::class)->group(function(){
//     Route::get('/users','index')->name('user.list');
//     Route::get('/users/create','create')->name('users.create');
// });


// // without controller view
// Route::get('/profile', function () { return view('supper-admin.profile'); });
// Route::get('/pos', function () { return view('supper-admin.pos'); });
