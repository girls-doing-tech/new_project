<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthController;
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
    return view('auth.register');
});/*
Route::get('/h', function () {
    return view('home');
});




Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'processRegistration'])->name('register.process');



Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'processLogin'])->name('login.process');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    return view('google2fa.index');
})->name('dashboard');


Route::get('/home', [RegisterController::class, 'register'])->name('home');
*/

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register',[AuthController::class,'register'])->name('register');
Route::post('/fa', [AuthController::class, 'checkTotp'])->name('fa')->middleware('auth');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login',[AuthController::class,'login'])->name('login.process');
/*Route::get('/fa', function () {
    if (!Auth::check()) {
        return redirect()->route('login'); 
    }else 
    return view('home');
});*/
//Route::group(['middleware'=> ['auth:sanctum']],function(){
    Route::middleware('web', 'auth')->group(function () { 
        Route::get('/home', [AuthController::class, 'index'])->name('home')
        ->middleware('totp.verification');
    Route::get('/complete-registration', function () {
        return view('home');
    })->name('complete-registration')->middleware('auth');;
Route::post('/logout',[AuthController::class,'logout'])->name('logout');

});


