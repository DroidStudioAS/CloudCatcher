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
    protected $signature = 'forecast:get';

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
            "q"=>"New York",
            "days"=>"5",
            "aqi"=>"no"

        ]);

        $jsonResponse = json_decode($response->body());

        dd($jsonResponse)->day;
    }
}
