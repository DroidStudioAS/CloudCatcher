<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetDailyForecast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forecast:daily {city}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $url = "http://api.weatherapi.com/v1/forecast.json";

        $response = Http::get($url, [
            "key" => env("WEATHER_API_KEY"),
            "q" => $this->argument("city"),
            "aqi"=>"yes"
        ]);

        $jsonResponse = json_decode($response->body(), true);

        // Initialize an associative array to store weather information
        $weatherInfo = [];

        // Check for errors
        if (isset($jsonResponse["error"])) {
            $weatherInfo['error'] = $jsonResponse["error"]["message"];
            $this->line(json_encode($weatherInfo));
            //failure
            return 1;
        }

        // Add city name and country to the array
        $weatherInfo['city_name'] = $jsonResponse["location"]["name"];
        $weatherInfo['country'] = $jsonResponse["location"]["country"];
        $weatherInfo['date'] = $jsonResponse["forecast"]["forecastday"][0]["date"];

        // Add forecast information for each day to the array (api only returns 3 days)
        $weatherInfo['forecast'] = [];

        $dayForecast = [
            "current_temp" => $jsonResponse["current"]["temp_c"],
            "wind_kph"=> $jsonResponse["current"]["wind_kph"],
            "sunrise"=>$jsonResponse["forecast"]["forecastday"][0]["astro"]["sunrise"],
            "sunset"=>$jsonResponse["forecast"]["forecastday"][0]["astro"]["sunset"],
            'max_temp' => $jsonResponse["forecast"]["forecastday"][0]["day"]["maxtemp_c"],
            'min_temp' => $jsonResponse["forecast"]["forecastday"][0]["day"]["mintemp_c"],
            'avg_temp' => $jsonResponse["forecast"]["forecastday"][0]["day"]["avgtemp_c"],
            'description' => $jsonResponse["forecast"]["forecastday"][0]["day"]["condition"]["text"],
            'chance_of_snow' => $jsonResponse["forecast"]["forecastday"][0]["day"]["daily_chance_of_snow"],
            'chance_of_rain' => $jsonResponse["forecast"]["forecastday"][0]["day"]["daily_chance_of_rain"],
            "aqi"=>$jsonResponse["current"]["air_quality"]["us-epa-index"]
        ];
        $weatherInfo['forecast'][] = $dayForecast;

        $this->line(json_encode($weatherInfo));
        //success
        return 0;
    }
}
