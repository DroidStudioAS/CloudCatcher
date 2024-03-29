<?php

namespace App\Console\Commands;

use App\Helpers\ParamHelper;
use App\Services\WeatherService;
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
    protected $signature = 'forecast:get {city?} {country?} {date?}';

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
        //build request parameters
        $q = ParamHelper::buildQ($this->argument("city"), $this->argument("country"));
        $dt = ParamHelper::buildDt($this->argument("date"));
        //make api call in weather service
        $jsonResponse = WeatherService::getForecast($q,$dt);
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
        //this is only in case date was not sent

            $weatherInfo['forecast'] = [];
            for ($i = 0; $i <= 2; $i++) {
                //If user sent a date param, we only want to retrieve the first forecast day: $i===0
                if($dt!==null){
                    if($i===1){
                        break;
                    }
                }
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
