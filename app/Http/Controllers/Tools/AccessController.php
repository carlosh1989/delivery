<?php

namespace App\Http\Controllers;

/* Models */
use App\User;
use App\UsersVideos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

/* Views support*/
// use Illuminate\Support\Facades\View;

/* Mail support*/
// use \Swift_Mailer;
// use \Swift_SmtpTransport;
// use \Swift_Message;

class AccessController extends Controller
{
    public static function Verify($request)
    {
        $idTokenString = $request->header('Authorization');

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/../../../../secret/delivery-6056d-35650ad5765b.json');
 

        $firebase = (new Factory)->withServiceAccount($serviceAccount)->create();

        try {
            $verifiedIdToken = $firebase->getAuth()->verifyIdToken($idTokenString);
        } catch (InvalidToken $e) {
            echo $e->getMessage();
        }

        $uid = $verifiedIdToken->getClaim('sub');
        $user = $firebase->getAuth()->getUser($uid);

        return $user;

        $user_exists = User::where('email', $user->email)->first();

        if(!$user_exists)
        {
            return response()->json(['status'=>'Unauthorized!'],401);
        }
    }
}
