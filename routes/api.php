<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['api', 'auth:sanctum'])->name('v1.')->prefix('v1')->group(function () {
    // API Resources
    Route::get('user_sennova', [ApiController::class, 'isUserSennova'])->name('user_sennova');
    Route::get('user_sennova/{id}/projects', [ApiController::class, 'projectsByUser'])->name('projects_by_user');
    Route::get('center/{id}/projects', [ApiController::class, 'projectsByCenter'])->name('projects_by_center');
    Route::get('projects/{id}', [ApiController::class, 'summaryProject'])->name('project');
});

