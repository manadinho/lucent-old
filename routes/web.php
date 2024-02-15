<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ExceptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectExceptionController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth', 'check.name'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::group(['prefix' => 'teams', 'as' => 'teams.'], function(){
        Route::get('/', [TeamController::class, 'index'])->name('index');
        Route::post('/', [TeamController::class, 'create'])->name('create');
        Route::get('/delete/{team}', [TeamController::class, 'delete'])->name('delete');
        Route::get('/{team}/members',[TeamController::class, 'members'])->name('members');
        Route::get('/{team}/projects',[TeamController::class, 'projects'])->name('projects');
    });

    Route::group(['prefix'=>'members','as'=>'members.'],function(){
        Route::post('/add',[MemberController::class, 'add'])->name('add');
       Route::get('remove/{user_id}/{team_id}',[MemberController::class, 'remove'])->name('remove');
    });
    
    Route::group(['prefix' => 'projects', 'as' => 'projects.'], function() {
        Route::post('/', [ProjectController::class, 'create'])->name('create');
        Route::get('/delete/{project}', [ProjectController::class, 'delete'])->name('delete');
        Route::get('/configurations/{project}', [ProjectController::class, 'configurations'])->name('configurations');
        Route::get('/key-generate/{project}', [ProjectController::class, 'keyGenerate'])->name('key.generate');
        Route::post('/store-openaikey', [ProjectController::class, 'storeOpenAiKey'])->name('store.openaikey');
        Route::post('/store-geminikey', [ProjectController::class, 'storeGeminikey'])->name('store.geminikey');

        Route::group(['prefix' => 'exceptions', 'as' => 'exceptions.'], function(){
            Route::get('/{project}', [ProjectExceptionController::class, 'index'])->name('index');
            Route::get('/fetch/{project}/{filter}', [ProjectExceptionController::class, 'fetch'])->name('fetch');
            Route::get('/fetch-count/{project}/{filter}', [ProjectExceptionController::class, 'count'])->name('fetch-count');
            Route::get('/fetch-chart-data/{project}/{filter}', [ProjectExceptionController::class, 'getMainChartData'])->name('fetch-main-chart-data');
            Route::get('/{project}/detail/{id}', [ProjectExceptionController::class, 'getOne'])->name('get-one');
            Route::post('/delete', [ProjectExceptionController::class, 'delete'])->name('delete');
            Route::post('/snooze', [ProjectExceptionController::class, 'markSnoozed'])->name('snooze');
            Route::post('/resolve', [ProjectExceptionController::class, 'markResolved'])->name('resolve');
            Route::get('/generate-solution/{exception}', [ProjectExceptionController::class, 'generateSolution'])->name('generate-solution');
        });
    });
});

Route::group(['middleware' => 'lucent.auth'], function(){
    Route::post('/register-exception', [ExceptionController::class, 'registerException']);
});

require __DIR__.'/auth.php';
