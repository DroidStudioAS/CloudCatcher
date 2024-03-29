<?php

namespace Database\Seeders;

use App\Helpers\WeatherHelper;
use App\Models\CityModel;
use App\Models\ForecastModel;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class ForecastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    //testing, ignore
    /*public function run()
    {
        foreach(CityModel::european_countries as $country=>$city){
            foreach ($city as $name){
                Artisan::call("forecast:get",[
                    "city"=>$name
                ]);
                $output=Artisan::output();

                if(str_contains($output, "Error")){
                    continue;
                }
                $this->command->getOutput()->writeln($output);

                if($name==="Pogradec"){
                    break;
                }
            }
        }
    }*/
   public function run()
    {
        $wHelper = new WeatherHelper();

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
                    "probability"=>$wHelper::returnProbability($description),
                    "path_to_image"=>$wHelper::determinePathToImage($description)
                ]);
                $this->command->getOutput()->progressAdvance(1);
            }
        }
        $this->command->getOutput()->progressFinish();

    }
}
