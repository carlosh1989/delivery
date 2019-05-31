<?php

namespace App\Http\Controllers;

/* Models */
use App\User;
use App\UsersVideos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/* Views support*/
// use Illuminate\Support\Facades\View;

/* Mail support*/
// use \Swift_Mailer;
// use \Swift_SmtpTransport;
// use \Swift_Message;

class ImageController extends Controller
{
    public static function upload($photo)
    {
		\Cloudinary::config(array( 
		    "cloud_name" => $_SERVER['CLOUD_NAME'],
		    "api_key" => $_SERVER['API_KEY'],
		    "api_secret" => $_SERVER['API_SECRET']
		));

		$arr_result = \Cloudinary\Uploader::upload($photo);
		
		return $arr_result['public_id'].'.png';
    }
}
