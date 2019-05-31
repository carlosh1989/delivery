<?php

use App\User;
use Faker\Factory as Faker;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class TalentsImages extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    function save_image($inPath,$outPath)
    { //Download images from remote server
        $in=    fopen($inPath, "rb");
        $out=   fopen($outPath, "wb");
        while ($chunk = fread($in,8192))
        {
            fwrite($out, $chunk, 8192);
        }
        fclose($in);
        fclose($out);
    }

    public function getimg($url) {         
        $headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';              
        $headers[] = 'Connection: Keep-Alive';         
        $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';         
        $user_agent = 'php';         
        $process = curl_init($url);         
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);         
        curl_setopt($process, CURLOPT_HEADER, 0);         
        curl_setopt($process, CURLOPT_USERAGENT, $user_agent); //check here         
        curl_setopt($process, CURLOPT_TIMEOUT, 30);         
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);         
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);         
        $return = curl_exec($process);         
        curl_close($process);         
        return $return;     
    } 


    public function run()
    {
        $users = User::all();

        foreach ($users as $key => $value) 
        {
            $path = 'uploads/profile-my-moment/'.$value->id;
            $newName = bin2hex(openssl_random_pseudo_bytes(16)).'.jpg';
            $pathComplete = $path.'/'.$newName;


            $url = 'https://loremflickr.com/cache/resized/defaultImage.small_320_240_nofilter.jpg';
            $img = $pathComplete;


            Image::make($url)->save(public_path($img));

            $myMoment = MyMoment::create([
                'users_id'   => $user['id'], 
                'filename'   =>  $pathComplete
            ]);
        }
    }
}
