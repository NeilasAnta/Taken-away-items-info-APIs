<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
use \App\Http\Controllers\StudentController;
use \App\Http\Controllers\AssignmentController;
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


Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware(['auth.jwt'])->group(function () {
    Route::get('/auth/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    Route::post('/auth/update-password', [AuthController::class, 'updatePassword']);

    Route::get('/inventory/show', [InventoryController::class, 'show']);
    Route::get('/inventory/responsible', [StudentController::class, 'showResponsibleInventory']);
    Route::post('/inventory/assign', [AssignmentController::class, 'assignInventory']);
    Route::post('/inventory/unassign', [AssignmentController::class, 'unassignInventory']);

});

Route::middleware(['auth.jwt', 'admin'])->group(function () {
    Route::get('/inventory/{id}', [InventoryController::class, 'getById']);
    Route::post('/inventory', [InventoryController::class, 'store']);
    Route::put('/inventory/{id}', [InventoryController::class, 'update']);
    Route::delete('/inventory/{id}', [InventoryController::class, 'delete']);

    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::delete('/auth/delete/{email}', [AuthController::class, 'delete']);

    Route::get('/auth/show-students', [StudentController::class, 'showAllStudents']);
});


Route::any('{any}', function(){
    return response()->json([
        'status' => 'error',
        'message' => 'Resource not found'], 404);
})->where('any', '.*')->name('notFound');


