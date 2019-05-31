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

class AccountController extends Controller
{
    function getAccount(Request $request)
    {
        $user =  User::where('api_token', $request->header('Authorization'))->first();  

        if($user)
        {
            $data = array(
                'name' => $user->name,
                'lastName' => $user->lastName,
                'email' => $user->email,
                'birthDate' => $user->birthDate,
                'actualCountry' => $user->actualCountry,
                'nationality' => $user->nationality,
                'otherNationality' => $user->otherNationality,
                'primaryPhone' => $user->primaryPhone,
                'secondaryPhone' => $user->secondaryPhone,
                'originCountry' => $user->originCountry,
                'passport' => $user->passport,
                'identificationCard' => $user->identificationCard,
                'address' => $user->address,
                'sector' => $user->sector,
                'city' => $user->city,
                'province' => $user->province
            );

            return response()->json($data, 200);
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
