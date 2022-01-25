<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\IpDatatableController;
use App\Http\Controllers\SubscribeDatatableController;
use App\Http\Controllers\UserController;

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
    return redirect()->route('main');
});

Route::get('/login', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('login');

// Route::middleware(['auth:sanctum', 'verified'])->group(function () {
//     Route::get('/dashboard', function () {
//         return Inertia::render('Dashboard');
//     })->name('dashboard');         
// });

Route::group(['middleware' => ['divert:make subscribe']], function () {        
    Route::get('/main', function(){
        return;
    })->name('main');
});

Route::group(['middleware' => ['permission:make subscribe']], function () {        
    Route::get('/manage', [IpDatatableController::class, 'manage_view'])->name('manage.view');
    Route::post('/subscribe/create', [SubscribeDatatableController::class, 'create']);
});

Route::group(['middleware' => ['permission:manage users']], function () {
    Route::get('/user', [UserController::class, 'user_view'])->name('user.view');
    Route::get('/user/SearchDynamic', [UserController::class, 'SearchDynamic']);
    Route::post('/user/query', [UserController::class, 'query']);
    Route::post('/user/update', [UserController::class, 'update']);
});

Route::group(['middleware' => ['permission:view']], function () {
    Route::get('/list', [IpDatatableController::class, 'list_view'])->name('list.view');
    Route::get('/history', [SubscribeDatatableController::class, 'history_view'])->name('history.view');

    Route::get('/ip/SearchDynamic', [IpDatatableController::class, 'SearchDynamic']);
    Route::post('/ip/ping', [IpDatatableController::class, 'Ping']);
    Route::post('/ip/query', [IpDatatableController::class, 'query']);

    Route::get('/subscribe/SearchDynamic', [SubscribeDatatableController::class, 'SearchDynamic']);
    Route::post('/subscribe/query', [SubscribeDatatableController::class, 'query']);
});

Route::get('/test', [IpDatatableController::class, 'Ping']);