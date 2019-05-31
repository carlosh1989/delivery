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

class ProductController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->json()->all();
        $photo = $request->file('photo');

		$upload = ImageController::upload($photo);

		return $upload;
    }
}
