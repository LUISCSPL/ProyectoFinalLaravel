<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return redirect()->route('login'); });

Route::get('/dashboard', function () {
    return redirect()->route('projects.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('projects', ProjectController::class);
    Route::patch('/projects/{project}/status', [ProjectController::class, 'updateProjectStatus'])->name('projects.updateStatus');
    Route::post('/projects/{project}/collaborator', [ProjectController::class, 'addCollaborator'])->name('projects.addCollaborator');
    Route::delete('/projects/{project}/collaborator/{user}', [ProjectController::class, 'removeCollaborator'])->name('projects.removeCollaborator');

    Route::post('/projects/{project}/tasks', [ProjectController::class, 'storeTask'])->name('projects.storeTask');
    Route::patch('/tasks/{task}/status', [ProjectController::class, 'updateTaskStatus'])->name('tasks.updateStatus');
    
    Route::get('/tasks/{task}/edit', [ProjectController::class, 'editTask'])->name('tasks.edit');
    Route::put('/tasks/{task}', [ProjectController::class, 'updateTask'])->name('tasks.update');
    Route::delete('/tasks/{task}', [ProjectController::class, 'destroyTask'])->name('tasks.destroy');
});

require __DIR__.'/auth.php';