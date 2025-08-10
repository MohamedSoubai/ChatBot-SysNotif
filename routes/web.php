<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ChatbotController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::controller(AuthController::class)->group(function () {
    Route::get('register', 'register')->name('register');
    Route::post('register', 'registerSave')->name('register.save');
  
    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginAction')->name('login.action');

    Route::get('auth/google',[GoogleAuthController::class,'redirect'])->name('google-auth');
    Route::get('auth/google/callback-url',[GoogleAuthController::class,'callback']);
  
    Route::get('logout', 'logout')->middleware('auth')->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/clients', function() {
        return redirect()->route('clients.index');
    });

    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::delete('/clients/{CodeC}', [ClientController::class, 'destroy'])->name('clients.destroy');
    Route::get('/clients/{CodeC}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{CodeC}', [ClientController::class, 'update'])->name('clients.update');
    Route::get('/clients/{CodeC}', [ClientController::class, 'show'])->name('clients.show');

    // Chatbot endpoint (secured)
    Route::post('/chat/query', [ChatbotController::class, 'query'])->name('chat.query');
    
    
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
});
Route::resource('factures', FactureController::class);   
Route::post('/factures/{id}/notify', [FactureController::class, 'notify'])->name('factures.notify');
