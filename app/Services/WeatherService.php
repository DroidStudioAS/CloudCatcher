<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    public static function getForecast($q, $dt){
        $response = Http::get(env("WEATHER_API_URL"), [
            "key" => env("WEATHER_API_KEY"),
            "q" => $q,
            "aqi" => "yes",
            "days" => 5,
            "dt"=>$dt
        ]);

        return $response->json();
    }
}
