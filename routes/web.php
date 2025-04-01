<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return redirect()->route('tasks.assigned.index');
    });

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::prefix('/tasks')->group(function () {
        Route::resource('/tasks', 'App\Http\Controllers\TaskCreatedController');
        Route::prefix('/assigned')->group(function () {
            Route::get('', [App\Http\Controllers\TaskAssignedController::class, 'index'])->name('tasks.assigned.index');
            Route::patch('/{id}/status', [App\Http\Controllers\TaskAssignedController::class, 'status'])->name('tasks.assigned.status');
        });
    });
});
Route::get('/test',function (){
     return DB::table('users')->take(100)->get();
});


//Route::get('/', function (){
//    Log::info("this is new log");
//});
//        Route::get('/', [App\Http\Controllers\TaskCreatedController::class, 'index'])->name('tasks.created.index');
//        Route::get('/create', [App\Http\Controllers\TaskCreatedController::class, 'create'])->name('tasks.created.create');
//        Route::post('/', [App\Http\Controllers\TaskCreatedController::class, 'store'])->name('tasks.created.store');
//        Route::get('/{id}', [App\Http\Controllers\TaskCreatedController::class, 'show'])->name('tasks.created.show');
//        Route::get('/{id}/edit', [App\Http\Controllers\TaskCreatedController::class, 'edit'])->name('tasks.created.edit');
//        Route::post('/{id}', [App\Http\Controllers\TaskCreatedController::class, 'update'])->name('tasks.created.update');
//        Route::get('/{id}', [App\Http\Controllers\TaskCreatedController::class, 'destroy'])->name('tasks.created.delete');
