<?php

use App\Http\Controllers\MaterialRequirementController;
use App\Http\Controllers\ProductPlanningController;
use App\Http\Controllers\PurchaseRequestController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/material_requirement', [MaterialRequirementController::class, 'index'])->name('material_requirement.index');
Route::get('/mr', [MaterialRequirementController::class, 'index1'])->name('material_requirement.index');
Route::get('/purchase_request', [PurchaseRequestController::class, 'index'])->name('purchase_request.index');
Route::post('/purchase_request', [PurchaseRequestController::class, 'store'])->name('purchase_request.store');
Route::get('/production_planning', [ProductPlanningController::class, 'index'])->name('production_planning.index');
Route::post('/production_planning', [ProductPlanningController::class, 'store'])->name('production_planning.store');
