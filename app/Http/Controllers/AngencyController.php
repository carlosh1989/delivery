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

class AngencyController extends Controller
{
    function angecyAll(Request $request)
    {
        $user =  User::where('api_token', $request->header('Authorization'))->first();  

        if($user)
        {
            $agency = User::where('userType',3)->get(['id','name']);
            return response()->json($agency, 200);
        }
        else
        {
            return response()->json(['error' => 'Unauthorized'] , 401 , []);
        }
    }

    function updateAccount(Request $request)
    {
        $data = $request->json()->all();

        unset($data['email']);

        $user =  User::where('api_token', $request->header('Authorization'))->first();
        
        if ($user) 
        {
            $user_update = User::find($user->id)->update($data);
            return response()->json( $user->id , 200 );
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }
}
