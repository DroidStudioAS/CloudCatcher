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
    protected $signature = 'forecast:get {city}';

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

        $response = Http::get($url,[
            "key"=>env("WEATHER_API_KEY"),
            "q"=> $this->argument("city"),
            "aqi"=>"no",
            "days"=>5

        ]);



        $jsonResponse = json_decode($response->body(), true);
        $output = "";
        //check for errors
        if(isset($jsonResponse["error"])){
            $output.="Error " . $jsonResponse["error"]["message"];
            $this->output->write($output);
            return $output;
        }

         //dd($jsonResponse);
        //api only returns todau + 2 days
        //city_name
        $output.= "City_name: " . $jsonResponse["location"]["name"] . " \n";
        //country
        $output.= "Country: " .  $jsonResponse["location"]["country"] . " \n";
        for($i = 0; $i<=2; $i++){
            //date
            $output.= "Date " . $jsonResponse["forecast"]["forecastday"][$i]["date"] . " ";
            //MaxTemp
            $output.= "Max Temp " . $jsonResponse["forecast"]["forecastday"][$i]["day"]["maxtemp_c"] . " ";
            //MinTemp
            $output.=  "Min Temp " . $jsonResponse["forecast"]["forecastday"][$i]["day"]["mintemp_c"] . " ";
            //average temp
            $output.=  "Average Temp " . $jsonResponse["forecast"]["forecastday"][$i]["day"]["avgtemp_c"] . " ";
            //condition
            $output.=  "Description " . $jsonResponse["forecast"]["forecastday"][$i]["day"]["condition"]["text"] . " ";
            //chance of snow
            $output.=  "Chance Of Snow " . $jsonResponse["forecast"]["forecastday"][$i]["day"]["daily_chance_of_snow"]. "% \n";
            //chance of rain
            $output.=  "Chance Of Rain " . $jsonResponse["forecast"]["forecastday"][$i]["day"]["daily_chance_of_rain"]. "% \n";

        }
        $this->output->write($output);
        return $output;
    }
}
