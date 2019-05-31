<?php

use App\User;
use App\UsersFeaturesDetail;
use Illuminate\Database\Seeder;

class UsersFeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $key => $user) 
        {
            $color_ojos = UsersFeaturesDetail::create([
                'features_values_id' => mt_rand(1,6),
                'users_id'           => $user->id,
            ]);

            $color_pelo = UsersFeaturesDetail::create([
                'features_values_id' => mt_rand(7,11),
                'users_id'           => $user->id,
            ]);

            $tipo_pelo = UsersFeaturesDetail::create([
                'features_values_id' => mt_rand(12,20),
                'users_id'           => $user->id,
            ]);

            $idioma = UsersFeaturesDetail::create([
                'features_values_id' => mt_rand(33,36),
                'users_id'           => $user->id,
            ]);

            $deporte = UsersFeaturesDetail::create([
                'features_values_id' => mt_rand(37,38),
                'users_id'           => $user->id,
            ]);

        }
    }
}
