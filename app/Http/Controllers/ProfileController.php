<?php

namespace App\Http\Controllers;

/* Models */
use App\User;
use App\ProfileGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/* Views support*/
use Illuminate\Support\Facades\View;

/* Mail support*/
use \Swift_Mailer;
use \Swift_SmtpTransport;
use \Swift_Message;

class ProfileController extends Controller
{
    function getMyGallery(Request $request)
    {

         if ($request->isJson()){
                
            $user =  User::where('api_token', $request->header('Authorization'))->first();
            
            if ($request->header('Authorization') == $user['api_token']) {
                
                $result = ProfileGallery::where('users_id',$user['id'])->get();
                return response()->json( ( $result->count() ) ? $result : [] , 200 );
            }
            else
            {

                return response()->json(['error','Unauthorized'] , 401 , []);
            }
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }

    function createProfilePhoto(Request $request){

        $user =  User::where('api_token', $request->header('Authorization'))
                ->where('userType', 1 ) // Client
                ->first();
        
        if (!$user)
        {
            return response()->json(['error','You are not a talent'] , 401 , []);
        }

        // Guardar los archivos en la galeria
        if ($request->hasFile('profile_image'))
        {   
            // Carpeta donde se guardaran las fotos
            $path = 'uploads/profile-photo/'.$user['id'];
            
            // Recorrer el array de fotos
            $photo = $request->file('profile_image');

            $newName = bin2hex(openssl_random_pseudo_bytes(16)).'.'.$photo->getClientOriginalExtension();
            // Guardar las fotos en la carpeta
            $photo->move($path,$newName);
            $user->profilePhoto = $path.'/'.$newName;
            $user->update();

            return response()->json( ['status'=>'Upload profile Photo','data'=>$user] , 201 );
        }
        else
        {
            return response()->json(['error'=>'Length Required'] , 411 , []); 
        }
    }
}
