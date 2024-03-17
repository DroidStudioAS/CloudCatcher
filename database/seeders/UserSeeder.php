<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $amountToCreate = $this->command->getOutput()->ask("How Many Users Should The Seeder Create",500);

        $passToSet = (string)$this->command->ask("What Should The Password Be", "12345678");

        $faker = Factory::create();

        $this->command->getOutput()->progressStart($amountToCreate);

        for($i=0; $i<$amountToCreate; $i++) {

            $username="user_" . $faker->userName;
            $password = $passToSet;
            $email=$faker->safeEmail();

            User::create([
                'name' => $username,
                "email" => $email,
                "password" => $password
            ]);
            $this->command->getOutput()->progressAdvance(1);
        }
        $this->command->getOutput()->progressFinish();



    }
}
