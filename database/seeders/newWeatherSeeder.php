<?php

namespace Database\Seeders;

use App\Models\CityModel;
use App\Models\WeatherModel;
use Illuminate\Database\Seeder;

class newWeatherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
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
        //end index
        $pathsEnd = count($pathsToImages)-1;

        $temperatureRange = [-10,30];

        $firstEntry = CityModel::first()->id;
        $lastEntry = CityModel::latest()->first()->id;


        $this->command->getOutput()->progressStart($lastEntry);
        for($i=$firstEntry; $i<=$lastEntry; $i++){
            //index of image path and description to insert
            $descriptionIndex = rand(0,$pathsEnd);

            WeatherModel::create([
                "city_id"=>$i,
                "temperature"=>rand($temperatureRange[0],$temperatureRange[1]),
                "description"=>$descriptions[$descriptionIndex],
                "path_to_image"=>$pathsToImages[$descriptionIndex]
            ]);

            $this->command->getOutput()->progressAdvance(1);
        }
        $this->command->getOutput()->progressFinish();

    }
}
