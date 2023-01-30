<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\CompanyController::class, 'index']);

Route::resource('companies', CompanyController::class);
Route::resource('employees', EmployeeController::class)->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy']);

Auth::routes(['register' => false]);


