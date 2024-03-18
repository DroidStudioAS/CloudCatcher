<?php

namespace Database\Seeders;

use App\Models\CityModel;
use App\Models\ForecastModel;
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
        //i will hardcode these values simply so i can test the 5 day forecast for all the cities,
        //the refactor to generate random dates will be fairly simple
        $dates=[
          "2024-03-18",
            "2024-04-18".
            "2024-05-18",
            "2024-06-18",
            "2024-07-18"
        ];
        //end index
        $pathsEnd = count($pathsToImages)-1;
        $temperatureRange = [-10,30];


        $startIndex = CityModel::first()->id;
        $endIndex = CityModel::latest()->first()->id;

        //loop through all cities
        //for all purposes, i is the id of the city we are referencing, j counts how many entries are entered
        $this->command->getOutput()->progressStart();
        for($i=$startIndex; $i<=3; $i++){
            //5 temps for each city with a random date need to be generated
            for($j=0; $j<=4; $j++){
             $pathIndex = rand(0,$pathsEnd);
             ForecastModel::create([
                 "city_id"=>$i,
                 "temperature"=>rand($temperatureRange[0],$temperatureRange[1]),
                 "date"=>$dates[$j],
                 "description"=>$descriptions[$pathIndex],
                 "path_to_image"=>$pathsToImages[$pathIndex]
             ]);
             $this->command->getOutput()->progressAdvance(1);
            }
        }
        $this->command->getOutput()->progressFinish();
    }
}
