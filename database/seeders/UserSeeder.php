<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Validator;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rulesForValidation = [
            "name" => "required|string",
            "email" => "required|string|email|unique:users",
            "password" => "required|string|min:8"
        ];
        $validator = Validator::make([
            "name" => $this->command->getOutput()->ask("What is the users name?"),
            "email" => $this->command->getOutput()->ask("What Is The Users Email"),
            "password" => $this->command->getOutput()->ask("What should the password be")
        ], $rulesForValidation);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->command->error($error);
            }
            exit();
        }

        $this->command->getOutput()->writeln("Passed validation...Creating User...");

        //validation passed
        User::create([
           "name"=>$validator->validated()["name"],
           "email"=>$validator->validated()["email"],
           "password"=>Hash::make($validator->validated()["password"])
        ]);

        $this->command->getOutput()->writeln("User " . $validator->validated()["name"] . " Created Successfully");





    }
}
