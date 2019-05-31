<?php 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class InsertUserSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $faker = Faker::create();
        foreach (range(1, 1000) as $index) {
            DB::table('users')->insert([
                
                'name'              => $faker->name,
                'email'             => $faker->unique()->email,
                "firstName"         => $faker->firstName,
                "userName"          => 'login'.mt_rand(100,1000),
                "email"             => $faker->email,
                "password"          => $faker->password,
                "remember_token"    => '',
                "api_token"         => str_random(60),
                "birthDateMonth"    => mt_rand(1,12),
                "birthDateDay"      => mt_rand(1,27),
                "birthDateYear"     => mt_rand(1900,2018),
                "verified"          => 1,
                "originCountry"     => $faker->country,
                "actualCountry"     => $faker->country,
                "primaryPhone"      => $faker->phoneNumber,
                "secondaryPhone"    => $faker->phoneNumber,
                "avatar"            => $faker->imageUrl(75, 75),
                "pathAvatar"        => mt_rand(99,9999)
            ]);
        }
    }
}


?>



