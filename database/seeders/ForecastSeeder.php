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
        //$this->command->getOutput()->progressStart();
        for($i=$startIndex; $i<$endIndex; $i++){
            $temperature = null;
            for($j=0; $j<=6; $j++){
                if($temperature===null){
                    $temperature=rand($temperatureRange[0],$temperatureRange[1]);
                }else{
                    $temperature=rand($temperature-5,$temperature+5);
                }

                ForecastModel::create([
                    "city_id"=>$i,
                    "temperature"=>$temperature,
                    "date"=>Carbon::now()->addDays($j),
                    "description"=>$descriptions[WeatherHelper::descriptionDeterminer($temperature)],
                    "probability"=>WeatherHelper::returnProbability($descriptions[WeatherHelper::descriptionDeterminer($temperature)]),
                    "path_to_image"=>WeatherHelper::determinePathToImage($descriptions[WeatherHelper::descriptionDeterminer($temperature)])
                ]);

            }
        }


    }
}
