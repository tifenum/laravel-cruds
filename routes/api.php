<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DepartmentTypeController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\CongeController;
use App\Http\Controllers\TypeDeCongeController;
use App\Http\Controllers\CandidatureController;

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

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('forget', [AuthController::class, 'forget']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
});

Route::post('/candidatures', [CandidatureController::class, 'store']);

Route::middleware(['jwt.verify'])->group(function () {
    Route::get('/users', [UserController::class, 'read']);
    Route::post('/users', [UserController::class, 'create']);
    Route::post('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'delete']);
    Route::get('/user/search', [UserController::class, 'search']);
    Route::get('/user/paginate', [UserController::class, 'paginate']);
    Route::put('/users/{id}/toggle-status', [UserController::class, 'toggleStatus']);

    Route::get('/account', [AccountController::class, 'me']);
    Route::post('/account', [AccountController::class, 'update']);
    Route::put('/account/password', [AccountController::class, 'password_change']);

    // Department Type Routes
    Route::get('/department-types', [DepartmentTypeController::class, 'index']);
    Route::post('/department-types', [DepartmentTypeController::class, 'store']);
    Route::get('/department-types/{id}', [DepartmentTypeController::class, 'show']);
    Route::put('/department-types/{id}', [DepartmentTypeController::class, 'update']);
    Route::delete('/department-types/{id}', [DepartmentTypeController::class, 'destroy']);

    // Contract Type Routes
    Route::get('/contract-types', [ContractTypeController::class, 'index']);
    Route::post('/contract-types', [ContractTypeController::class, 'store']);
    Route::get('/contract-types/{id}', [ContractTypeController::class, 'show']);
    Route::put('/contract-types/{id}', [ContractTypeController::class, 'update']);
    Route::delete('/contract-types/{id}', [ContractTypeController::class, 'destroy']);

    // Department Routes
    Route::get('/departments', [DepartmentController::class, 'index']);
    Route::post('/departments', [DepartmentController::class, 'store']);
    Route::get('/departments/{id}', [DepartmentController::class, 'show']);
    Route::put('/departments/{id}', [DepartmentController::class, 'update']);
    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy']);

    // Contract Routes
    Route::get('/contracts', [ContractController::class, 'index']);
    Route::post('/contracts', [ContractController::class, 'store']);
    Route::get('/contracts/{id}', [ContractController::class, 'show']);
    Route::put('/contracts/{id}', [ContractController::class, 'update']);
    Route::delete('/contracts/{id}', [ContractController::class, 'destroy']);
    Route::get('/contracts/{id}/file', [ContractController::class, 'downloadContract']);

    // Conge Routes
    Route::get('/conges', [CongeController::class, 'index']);
    Route::post('/conges', [CongeController::class, 'store']);
    Route::get('/conges/{id}', [CongeController::class, 'show']);
    Route::put('/conges/{id}', [CongeController::class, 'update']);
    Route::delete('/conges/{id}', [CongeController::class, 'destroy']);
    Route::patch('/conges/{id}/accepter', [CongeController::class, 'accepterConge']);
    Route::patch('/conges/{id}/refuser', [CongeController::class, 'refuserConge']);
    Route::get('/conges/not-studied', [CongeController::class, 'notStudied']);

    // TypeDeConge Routes
    Route::get('/type-de-conges', [TypeDeCongeController::class, 'index']);
    Route::post('/type-de-conges', [TypeDeCongeController::class, 'store']);
    Route::get('/type-de-conges/{id}', [TypeDeCongeController::class, 'show']);
    Route::put('/type-de-conges/{id}', [TypeDeCongeController::class, 'update']);
    Route::delete('/type-de-conges/{id}', [TypeDeCongeController::class, 'destroy']);
    
    Route::get('/candidatures', [CandidatureController::class, 'index']);
    Route::put('/candidatures/{id}/status/{status}', [CandidatureController::class, 'updateStatus']);
    Route::delete('/candidatures/{id}', [CandidatureController::class, 'destroy']);
    Route::get('candidatures/{id}/cv', [CandidatureController::class, 'downloadCV']);
    Route::get('candidatures/{id}/download-lettre', [CandidatureController::class, 'downloadLettre']);

});
