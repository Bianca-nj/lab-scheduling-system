<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchedulingRequestController;
use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SettingsController;

Route::get('/login',[AuthController::class,'showLogin']);
Route::post('/login',[AuthController::class,'login']);
Route::get('/logout',[AuthController::class,'logout']);

Route::middleware('staff.auth')->group(function () {

    Route::get('/', [SchedulingRequestController::class, 'create']);
    Route::post('/request/store', [SchedulingRequestController::class, 'store']);
    Route::get('/requests/rejected', [SchedulingRequestController::class, 'rejected']);

    Route::get('/laboratories', [LaboratoryController::class, 'index']);
    Route::post('/laboratories/store', [LaboratoryController::class, 'store']);
    Route::get('/laboratories/edit/{id}', [LaboratoryController::class, 'edit']);
    Route::post('/laboratories/update/{id}', [LaboratoryController::class, 'update']);
    Route::get('/laboratories/delete/{id}', [LaboratoryController::class, 'destroy']);

    Route::get('/settings', [SettingsController::class, 'index']);
    Route::post('/settings/password', [SettingsController::class, 'updatePassword']);

    Route::get('/resources', [ResourceController::class, 'index']);
    Route::post('/resources/store', [ResourceController::class, 'store']);
    Route::post('/resources/update/{id}', [ResourceController::class, 'update']);
    Route::get('/resources/delete/{id}', [ResourceController::class, 'destroy']);

    Route::get('/schedules', [ScheduleController::class, 'index']);

    Route::post(
        '/suggestion/accept',
        [SchedulingRequestController::class, 'acceptRecommendation']
    );

    Route::post(
        '/suggestion/decline',
        [SchedulingRequestController::class, 'rejectRecommendation']
    );

    Route::post(
        '/suggestion/accept-time',
        [SchedulingRequestController::class, 'acceptTimeSuggestion']
    );

    Route::post(
        '/suggestion/reject-time',
        [SchedulingRequestController::class, 'rejectTimeSuggestion']
    );

    Route::post(
        '/suggestion/accept-projector',
        [SchedulingRequestController::class, 'acceptProjectorSuggestion']
    );

    Route::post(
        '/suggestion/decline-projector',
        [SchedulingRequestController::class, 'declineProjectorSuggestion']
    );

    Route::get('/dashboard', function () {
        return view('dashboard', [
            'labCount' => \App\Models\Laboratory::count(),
            'resourceCount' => \App\Models\Resource::count(),
            'requestCount' => \App\Models\SchedulingRequest::count(),
            'scheduleCount' => \App\Models\Schedule::count(),
        ]);
    });

});