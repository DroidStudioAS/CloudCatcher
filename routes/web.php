<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\WeatherController;
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
Route::middleware('auth')->group(function (){
    Route::get("/",[HomeController::class,"toLogin"]);
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get("/welcome",function (){
        return view("welcome");
    });
    Route::get("/weather", [WeatherController::class, 'getAllWeathers']);


//admin routes
    Route::get("/admin",[WeatherController::class, 'getAllWeathersAdmin']);
    Route::post('admin/postWeather',[WeatherController::class,'postWeatherEntry'])
        ->name('post-weather');
    Route::post('/admin/edit-entry/{weather}',[WeatherController::class,'editWeatherEntry']);
    Route::post('/admin/delete-entry/{weather}',[WeatherController::class,'deleteWeatherEntry']);
});



Auth::routes();



