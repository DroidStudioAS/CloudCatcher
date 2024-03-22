<?php

namespace Database\Seeders;

use App\Helpers\WeatherHelper;
use App\Models\CityModel;
use App\Models\WeatherModel;
use Illuminate\Database\Seeder;

class WeatherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return int
     */

    public function run()
    {
        $wHelper = new WeatherHelper();
        $temperatureRange = [-10,30];

        $startIndex = CityModel::get()->first()->id;
        $endIndex = CityModel::all()->last()->id;
        //


        $this->command->getOutput()->progressStart();
        //execution i=city_id
        for($i=$startIndex; $i<=$endIndex; $i++){
            $temperature = rand($temperatureRange[0],$temperatureRange[1]);
            $description = $wHelper::descriptionDeterminer($temperature);
            $indexOfDescAndImage=WeatherHelper::descriptionDeterminer($temperature);
           WeatherModel::create([
               "city_id"=>$i,
               "temperature"=>$temperature,
               "description"=>$description,
               "path_to_image"=>$wHelper::determinePathToImage($description)
           ]);
           $this->command->getOutput()->progressAdvance(1);
        }
        $this->command->getOutput()->progressFinish();
    }

}
