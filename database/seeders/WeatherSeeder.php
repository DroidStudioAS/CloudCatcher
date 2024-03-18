<?php

namespace Database\Seeders;

use App\Models\WeatherModel;
use Illuminate\Database\Seeder;
//import this for the determineimagepath function
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Validator;

class WeatherSeeder extends Seeder
{
   public function determineDescriptionString($index){
       $descriptionString = "";
       switch ($index){
           case 1:
               $descriptionString= "sunny";
               break;
           case 2:
               $descriptionString =  "cloudy";
               break;
           case 3:
               $descriptionString = "raining";
               break;
           case 4:
               $descriptionString = "snowing";
               break;
       }
       return $descriptionString;
   }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rulesForValidation = [
          "city"=>"required|string|unique:weather",
          "temperature"=>"required|int",
            "description"=>"required|int|gte:1|lte:4"
        ];

        $validator = Validator::make(
            [
                "city"=>$this->command->getOutput()->ask("Enter The City You Are Recording Data For:"),
                "temperature"=>$this->command->getOutput()->ask("What is the Temperature"),
                "description"=>$this->command->getOutput()->ask("Is It 1)Sunny 2)Cloudy 3)Raining 4)Snowing (Enter the index)")
            ], $rulesForValidation);

        if($validator->fails()){
            foreach ($validator->errors()->all() as $error){
                $this->command->error($error);
            }
            exit();
        }

            $city = $validator->validated()["city"];
            $temperature = $validator->validated()["temperature"];
            $description = $this->determineDescriptionString($validator->validated()["description"]);
            $pathToImage = WeatherController::determinePathToimage($description);

            WeatherModel::create([
                "city"=>$city,
                "temperature"=>$temperature,
                "description"=>$description,
                "path_to_image"=>$pathToImage
            ]);

    }
}
