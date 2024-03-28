<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

class GetForecast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forecast:get {city} {country?}';

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

        //build the q param
        $q=$this->argument("city");
        if($this->argument("country")!==null){
            $q.= ", " . $this->argument("country");
        }



        $response = Http::get($url, [
            "key" => env("WEATHER_API_KEY"),
            "q" => $q,
            "aqi" => "no",
            "days" => 5
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

        // Add forecast information for each day to the array (api only returns 3 days)
        $weatherInfo['forecast'] = [];
        for ($i = 0; $i <= 2; $i++) {
            $dayForecast = [
                'date' => $jsonResponse["forecast"]["forecastday"][$i]["date"],
                'max_temp' => $jsonResponse["forecast"]["forecastday"][$i]["day"]["maxtemp_c"],
                'min_temp' => $jsonResponse["forecast"]["forecastday"][$i]["day"]["mintemp_c"],
                'avg_temp' => $jsonResponse["forecast"]["forecastday"][$i]["day"]["avgtemp_c"],
                'description' => $jsonResponse["forecast"]["forecastday"][$i]["day"]["condition"]["text"],
                'chance_of_snow' => $jsonResponse["forecast"]["forecastday"][$i]["day"]["daily_chance_of_snow"],
                'chance_of_rain' => $jsonResponse["forecast"]["forecastday"][$i]["day"]["daily_chance_of_rain"],
            ];
            $weatherInfo['forecast'][] = $dayForecast;
        }
        $this->line(json_encode($weatherInfo));
        //success
        return 0;
    }

}
