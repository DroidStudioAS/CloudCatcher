<?php

namespace Database\Seeders;

use App\Helpers\WeatherHelper;
use App\Models\CityModel;
use App\Models\ForecastModel;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ForecastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //important: for now the paths and the descriptions need to be in symmetric order so there is not a discrepancy in the frontend!
        $pathsToImages = [
            "res/sunny.png",
            "res/rainy.png",
            "res/snowy.png",
            "res/cloudy.png"
        ];
        $descriptions=[
            "Sunny",
            "Raining",
            "Snowing",
            "Cloudy"
        ];
        $temperatureRange = [-10,30];


        $startIndex = CityModel::first()->id;
        $endIndex = CityModel::all()->last()->id;

        //loop through all cities
        //for all purposes, i is the id of the city we are referencing, j counts how many entries are entered
        $this->command->getOutput()->progressStart();
        for($i=$startIndex; $i<=$endIndex; $i++){
            //5 temps for each city with a random date need to be generated
            for($j=0; $j<=6; $j++){
             $temperature = rand($temperatureRange[0],$temperatureRange[1]);
             $indexOfImagePathAndDescription = WeatherHelper::descriptionDeterminer($temperature);
             ForecastModel::create([
                 "city_id"=>$i,
                 "temperature"=> $temperature,
                 "date"=>Carbon::now()->addDays($j),
                 "description"=>$descriptions[$indexOfImagePathAndDescription],
                 "probability"=>WeatherHelper::returnProbability($indexOfImagePathAndDescription),
                 "path_to_image"=>$pathsToImages[$indexOfImagePathAndDescription]
             ]);
             $this->command->getOutput()->progressAdvance(1);
            }
        }
        $this->command->getOutput()->progressFinish();
    }
}
