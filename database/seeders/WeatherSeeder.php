<?php

namespace Database\Seeders;

use App\Models\WeatherModel;
use Illuminate\Database\Seeder;
//import this for the determineimagepath function
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Validator;
use App\Helpers\WeatherHelper;

class WeatherSeeder extends Seeder
{


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
            $description = WeatherHelper::determineDescriptionString($validator->validated()["description"]);
            $pathToImage = WeatherController::determinePathToimage($description);

            WeatherModel::create([
                "city"=>$city,
                "temperature"=>$temperature,
                "description"=>$description,
                "path_to_image"=>$pathToImage
            ]);

    }
}
