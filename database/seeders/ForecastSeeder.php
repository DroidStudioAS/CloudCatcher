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
    //WHEN RAN, IT WILL SEED TODAY AND THE NEXT 6 DAYS
    public function run()
    {
        $temperatureRange = [-10,30];

        $startIndex = CityModel::first()->id;
        $endIndex = CityModel::all()->last()->id;
        $this->command->getOutput()->progressStart();
        for($i=$startIndex; $i<$endIndex; $i++){
            $temperature = null;
            for($j=0; $j<=6; $j++){
                //set the temperature to a random num if there is not a temp for yesterday
                //if there is, set the temp to a number either 5 larger or 5 smaller
                if($temperature===null){
                    $temperature=rand($temperatureRange[0],$temperatureRange[1]);
                }else{
                    $temperature=rand($temperature-5,$temperature+5);
                }
                $description = WeatherHelper::descriptionDeterminer($temperature);
                ForecastModel::create([
                    "city_id"=>$i,
                    "temperature"=>$temperature,
                    "date"=>Carbon::now()->addDays($j),
                    "description"=>$description,
                    "probability"=>WeatherHelper::returnProbability($description),
                    "path_to_image"=>WeatherHelper::determinePathToImage($description)
                ]);
                $this->command->getOutput()->progressAdvance(1);
            }
        }
        $this->command->getOutput()->progressFinish();

    }
}
