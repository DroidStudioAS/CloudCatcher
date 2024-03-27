<?php

namespace App\Console\Commands;

use http\Env;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetWeather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grabs Real Time Weather From OpenWeatherAPI';

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
        $url = "https://reqres.in/api/users?page=2n";


        $response = Http::withOptions([
            "verify"=>false
        ])->get($url);
        dd($response);

       /*$url = "http://api.weatherapi.com/v1/current.json";
       $response = Http::get($url,[
           "key"=>env("WEATHER_API_KEY"),
           "q"=>$this->argument("city")
       ]);

       $code = $response->status();
       $json_response = json_decode($response->body(), true);
       if(isset($json_response["error"])){
           $this->getOutput()->error($json_response["error"]["message"]);
           exit();
       }
        $this->getOutput()->writeln($json_response["location"]["name"] . ":" . $json_response["current"]["temp_c"] . " Celsius");
    */
    }
}
