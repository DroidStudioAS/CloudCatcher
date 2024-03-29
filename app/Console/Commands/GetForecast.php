<?php

namespace App\Console\Commands;

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

        //build the q and dt param
        $q= "";
        $dt="";
        if($this->argument("city")!==null){
            $q.= $this->argument("city") .",";
        }
        if($this->argument("country")!==null){
            $q.= $this->argument("country");
        }
        if($this->argument("date")!==null){
            $dt=$this->argument("date");
        }


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
                //if user sent a date, we only want to retrive the first forecast day
                if($this->argument("date")!==null){
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
