<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminDashboardController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Dashboard Routes
    Route::prefix('dashboard')->group(function () {
        
        Route::get('/admin-stats', [AdminDashboardController::class, 'adminDashboard'])->middleware('permission:dashboard.view');
        Route::get('/stats', [DashboardController::class, 'getDashboardStats'])->middleware('permission:dashboard.view');
        Route::get('/team-stats', [DashboardController::class, 'getTeamDashboard'])->middleware('permission:dashboard.view');
        Route::get('/quick-actions', [DashboardController::class, 'getQuickActions'])->middleware('permission:dashboard.view');
    });

    Route::prefix('profile')->group(function () {
        Route::post('/updateProfile', [UsersController::class, 'updateProfile'])->middleware('permission:profile.update');
        Route::post('/updatePassword', [UsersController::class, 'updatePassword'])->middleware('permission:profile.password');
    });

    Route::prefix('system')->group(function () {
        Route::get('/getSingleSettings/{name}', [SystemSettingController::class, 'getSingleSettings'])->middleware('permission:system-settings.view');
        Route::post('/storeOrUpdateSettings/{name}', [SystemSettingController::class, 'storeOrUpdateSettings'])->middleware('permission:system-settings.create,system-settings.edit');
    });

    Route::get('/getPipeLineBoard', [LeadController::class, 'getPipeLineBoard'])->middleware('permission:lead.pipeline-board');
    Route::get('/leads/getMyLeads', [LeadController::class, 'getMyLeads'])->middleware('permission:lead.view');

    Route::prefix('leads')->group(function () {
        Route::get('/', [LeadController::class, 'index'])->middleware('permission:lead.view');
        Route::post('/storeOrUpdate/{id?}', [LeadController::class, 'storeOrUpdate'])->middleware('permission:lead.create,lead.edit');
        Route::get('/{id}', [LeadController::class, 'show'])->middleware('permission:lead.view');
        Route::delete('/{id}', [LeadController::class, 'destroy'])->middleware('permission:lead.delete');
        Route::post('/{id}/last-contacted', [LeadController::class, 'updateLastContacted'])->middleware('permission:lead.update-last-contacted');
        Route::post('/{id}/update-status', [LeadController::class, 'updateStatus'])->middleware('permission:lead.edit');
        Route::post('/{id}/assign', [LeadController::class, 'assignLead'])->middleware('permission:lead.edit');

    });

    Route::prefix('clients')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->middleware('permission:client.view');
        Route::post('/storeOrUpdate/{id?}', [ClientController::class, 'storeOrUpdate'])->middleware('permission:client.create,client.edit');
        Route::get('/{id}', [ClientController::class, 'show'])->middleware('permission:client.view');
        Route::delete('/{id}', [ClientController::class, 'destroy'])->middleware('permission:client.delete');
    });

    Route::prefix('meetings')->group(function () {
        Route::get('/', [MeetingController::class, 'index'])->middleware('permission:meeting.view');
        Route::post('/store', [MeetingController::class, 'store'])->middleware('permission:meeting.create');
        Route::get('/{id}', [MeetingController::class, 'show'])->middleware('permission:meeting.view');
        Route::post('/update/{id}', [MeetingController::class, 'update'])->middleware('permission:meeting.edit');
        Route::delete('/{id}', [MeetingController::class, 'destroy'])->middleware('permission:meeting.delete');
    });

    Route::prefix('user')->group(function () {
        // Roles & Permissions
        Route::get('permission-groups', [PermissionController::class, 'permissionGroups'])->middleware('permission:permission-groups.view');
        Route::post('permission-group/store', [PermissionController::class, 'permissionGroupStore'])->middleware('permission:permission-groups.create');
        Route::get('permission-group/edit/{id}', [PermissionController::class, 'permissionGroupEdit'])->middleware('permission:permission-groups.edit');
        Route::delete('permission-group/delete', [PermissionController::class, 'permissionGroupDelete'])->middleware('permission:permission-groups.delete');
        Route::get('permission-default', [PermissionController::class, 'permissionsDefault'])->middleware('permission:roles.view');

        // Users
        Route::post('/userStore', [PermissionController::class, 'userStore'])->middleware('permission:users.create');
        Route::get('/allUsers', [PermissionController::class, 'allUsers'])->middleware('permission:users.view');

        //Enable/Disable all users against specific company (excluding super admin and admin)
        Route::get('/enableDisableUser', [PermissionController::class, 'enableDisableUser'])->middleware('permission:users.edit');
    });

    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->middleware('permission:settings.view');
        Route::post('/storeOrUpdate', [SettingController::class, 'storeOrUpdate'])->middleware('permission:settings.create,settings.edit');
        Route::post('/notifications', [SettingController::class, 'updateNotificationPreferences'])->middleware('permission:settings.edit');
        Route::post('/custom-fields', [SettingController::class, 'addCustomField'])->middleware('permission:settings.custom-field');
        Route::delete('/custom-fields/{fieldId}', [SettingController::class, 'deleteCustomField'])->middleware('permission:settings.custom-field');
        Route::get('searchTimezone', [SettingController::class, 'searchTimezone'])->middleware('permission:settings.view');
        Route::get('getLanguages', [SettingController::class, 'getLanguages'])->middleware('permission:settings.view');
    });


    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->middleware('permission:task.view');
        Route::post('/storeOrUpdate/{id?}', [TaskController::class, 'storeOrUpdate'])->middleware('permission:task.create,task.edit');
        Route::get('/my-tasks', [TaskController::class, 'getUserTasks'])->middleware('permission:task.view');
        Route::get('/{id}', [TaskController::class, 'show'])->middleware('permission:task.view');
        Route::delete('/{id}/delete', [TaskController::class, 'destroy'])->middleware('permission:task.delete');
        Route::post('/{id}/update-status', [TaskController::class, 'updateStatus'])->middleware('permission:task.edit');
        Route::post('/{id}/assign', [TaskController::class, 'assignTask'])->middleware('permission:task.edit');
        Route::post('/{id}/add-note', [TaskController::class, 'addNote'])->middleware('permission:task.edit');
    });

    Route::prefix('companies')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->middleware('permission:company.view');
        Route::post('/storeOrUpdateWithAdmin/{id?}', [CompanyController::class, 'storeOrUpdateWithAdmin'])->middleware('permission:company.create');
        Route::get('/{id}', [CompanyController::class, 'show'])->middleware('permission:company.view');
        Route::post('/{id}/delete', [CompanyController::class, 'destroy'])->middleware('permission:company.delete');
        Route::post('/check-subdomain', [CompanyController::class, 'checkSubdomain'])->middleware('permission:company.check-subdomain');

        // Update company and user account status
        Route::post('/{id}/update-status', [CompanyController::class, 'updateStatus'])->middleware('permission:company.update-status');
    });
});
