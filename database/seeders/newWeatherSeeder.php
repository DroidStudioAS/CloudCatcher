<?php

namespace Database\Seeders;

use App\Helpers\WeatherHelper;
use App\Models\CityModel;
use App\Models\WeatherModel;
use Illuminate\Database\Seeder;

class newWeatherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return int
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
        $temperatureRange = [-10,30];

        $startIndex = CityModel::get()->first()->id;
        $endIndex = CityModel::all()->last()->id;
        //


        $this->command->getOutput()->progressStart();
        //execution i=city_id
        for($i=$startIndex; $i<=$endIndex; $i++){
            $temperature = rand($temperatureRange[0],$temperatureRange[1]);
            $indexOfDescAndImage=WeatherHelper::descriptionDeterminer($temperature);
           WeatherModel::create([
               "city_id"=>$i,
               "temperature"=>$temperature,
               "description"=>$descriptions[$indexOfDescAndImage],
               "path_to_image"=>$pathsToImages[$indexOfDescAndImage]
           ]);
           $this->command->getOutput()->progressAdvance(1);
        }
        $this->command->getOutput()->progressFinish();
    }

}
