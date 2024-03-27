<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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
         //dd($jsonResponse);
        //api only returns todau + 2 days
        for($i = 0; $i<=2; $i++){
            //city_name
            $output.=$jsonResponse["location"]["name"] . " ";
            //country
            $output.=$jsonResponse["location"]["country"] . " ";
            //date
            $output.=$jsonResponse["forecast"]["forecastday"][$i]["date"] . " ";
            //MaxTemp
            $output.=$jsonResponse["forecast"]["forecastday"][$i]["day"]["maxtemp_c"] . " ";
            //MinTemp
            $output.=$jsonResponse["forecast"]["forecastday"][$i]["day"]["mintemp_c"] . " ";
            //condition
            $output.=$jsonResponse["forecast"]["forecastday"][$i]["day"]["condition"]["text"] . " ";
            //chance of rain
            $output.=$jsonResponse["forecast"]["forecastday"][$i]["day"]["daily_chance_of_rain"]. "% \n";
        }


        $this->output->write($output);
    }
}
