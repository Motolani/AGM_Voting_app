<?php

// use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnalyticController;
use App\Http\Controllers\ItemController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth', 'is_shareholder'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
});
Route::post('/votingPost/{id}', [App\Http\Controllers\ItemController::class, 'voting'])->name('votingPost');
// Route::get('/voteView/{id}', [App\Http\Controllers\ItemController::class, 'voteView'])->name('voteView');

Route::get('admin/register',  [AdminController::class, 'registerView'])->name('admin.register');
Route::post('admin/registerPost',  [AdminController::class, 'register'])->name('admin.register.post');

Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {
    Route::get('home',  [AdminController::class, 'adminDashboard']);
    Route::get('shareholders',  [AdminController::class, 'viewShareholders'])->name('shareholders');
    Route::get('createShareholder',  [AdminController::class, 'createShareholder'])->name('create.shareholders');
    Route::post('createShareholder',  [AdminController::class, 'createShareholderPost'])->name('create.shareholders.post');
    
});


Route::get('analytics/{id}', [AnalyticController::class, 'googleLineChart']);
Route::get('createMeeting', [ItemController::class, 'meeting'])->name('createMeeting');
Route::get('newCreate/{id}', [ItemController::class, 'newCreate'])->name('newCreate');

Route::post('closeVote',  [ItemController::class, 'closeVote'])->name('closeVote');
Route::post('meetingPost',  [ItemController::class, 'meetingPost'])->name('meetingPost');
Route::get('meetingIndex',  [ItemController::class, 'meetingIndex'])->name('meetingIndex');
Route::get('viewItems/{id}',  [ItemController::class, 'viewItems'])->name('viewItems');

Route::delete('dropShareholder', [AdminController::class, 'dropShareholder'])->name('dropShareholder');
Route::resource('items', ItemController::class);
