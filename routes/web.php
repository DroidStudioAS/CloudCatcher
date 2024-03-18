<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\WeatherController;
use App\Http\Middleware\AdminMiddleware;
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
//user routes
Route::middleware('auth')->group(function (){
    Route::get("/",[HomeController::class,"toLogin"]);
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get("/weather",[WeatherController::class, 'loadTodaysWeathers']);
    Route::get("/weather/{date?}",[WeatherController::class,'getWeathersForDate'])->name("getWeatherForDate");
    Route::get("/all-time-weather",[WeatherController::class,'getAllWeathers'])->name("alltime");
});
//admin routes
Route::middleware(['auth', AdminMiddleware::class])
    ->prefix('admin')
    ->group(function (){
        Route::get("/",[WeatherController::class, 'getAllWeathersAdmin']);
        Route::post('postWeather',[WeatherController::class,'postWeatherEntry'])
            ->name('post-weather');
        Route::post('/edit-entry/{weather}',[WeatherController::class,'editWeatherEntry']);
        Route::post('/delete-entry/{weather}',[WeatherController::class,'deleteWeatherEntry']);
});

Route::get("/weather-for/{city}",[WeatherController::class,"getWeatherForecastForCity"]);

Auth::routes();



