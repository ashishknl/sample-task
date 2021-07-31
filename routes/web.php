<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;

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

Route::get("reboot",function (){
   Artisan::call('config:cache');
   Artisan::call('route:clear');
   Artisan::call('view:clear');
   Artisan::call('cache:clear');
   dd("Ready to Re-start");
});
Route::get('/getdump', [UserController::class, 'index']);
Route::get('/',[UserController::class, 'ShowTasks']);
Route::patch('demos/tasks/{id}', [UserController::class, 'updateTasksStatus']);
Route::delete('demos/tasks/{id}', [UserController::class, 'deleteTask']);
Route::put('demos/tasks/updateAll', [UserController::class, 'updateTasksOrder']);
Route::post('demos/column/insert', [UserController::class, 'columnInsert']);
Route::post('demos/task/insert', [UserController::class, 'taskInsert']);
Route::patch('demos/tasks/update/{id}', [UserController::class, 'updateTitleDesc']);
