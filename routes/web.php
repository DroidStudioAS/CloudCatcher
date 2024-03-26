<?php

use App\Http\Controllers\CityController;
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
    Route::get("/weather-for/{city}",[WeatherController::class,"getWeatherForecastForCity"])->name("forecast-city.permalink");
    Route::get("/weather-for-country/{country}",[WeatherController::class,"getCountryForecast"]);
    Route::get("/weather-searchAll",[ WeatherController::class,"searchAll"])->name("search");

    Route::post("add-user-favourite/{city}",[\App\Http\Controllers\UserCitiesController::class, "addToFavorites"]);
});
//admin routes
Route::middleware(['auth', AdminMiddleware::class])
    ->prefix('admin')
    ->group(function (){
        Route::get("/",[App\Http\Controllers\admin\WeatherController::class, 'getAllWeathersAdmin']);
        Route::post('/edit-entry/{weather}',[App\Http\Controllers\admin\WeatherController::class,'editWeatherEntry'])->name("edit-weather");
        Route::post('/delete-entry/{weather}',[App\Http\Controllers\admin\WeatherController::class,'deleteWeatherEntry']);
        Route::post('/post-forecast', [App\Http\Controllers\admin\WeatherController::class,'postForecastEntry']);
        Route::post("/delete-forecast/{forecast}",[App\Http\Controllers\admin\WeatherController::class,"deleteForecastEntry"]);
        Route::post("/add-city",[CityController::class,"addCity"])
            ->name("addCity");
});
Route::get("/testing", [WeatherController::class,'test']);


Auth::routes();



