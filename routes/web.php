<?php

use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use Faker\Guesser\Name;
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

//dd(config('app.url').'/register');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::group(['prefix' => 'teams', 'as' => 'teams.'], function(){
        Route::get('/', [TeamController::class, 'index'])->name('index');
        Route::post('/', [TeamController::class, 'create'])->name('create');
        Route::get('/delete/{id}', [TeamController::class, 'delete'])->name('delete');
        Route::get('/info/{id}',[TeamController::class,'teamsInfo'])->name('info');
});
   

});

Route::group(['prefix'=>'members','as'=>'members.'],function(){
    Route::post('/add',[MemberController::class,'add'])->name('add');
   Route::get('/invite',[MemberController::class,'acceptInvitation'])->name('invite');
   Route::get('remove/{user_id}/{team_id}',[MemberController::class,'remove'])->name('remove');
});

require __DIR__.'/auth.php';