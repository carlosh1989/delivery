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

class Videoscontroller extends Controller
{
    function assingVideos( Request $request )
    {
        //Verificar que es el propietario de la cuenta
        $data = $request->json()->all();
        
        $user =  User::where('api_token', $request->header('Authorization'))->first();
        
        if ($user) 
        {
            $videos = $data['videos'];

            foreach($videos as $video )
            {
                $url = $video['url'];
                list($uri,$id) = explode('=', $url);
                $thumbnail = 'https://img.youtube.com/vi/'.$id.'/0.jpg';

                UsersVideos::create([
                    'url' => $video['url'],
                    'thumbnail' => $thumbnail,
                    'users_id' => $user->id
                ]);

            }

            $result = UsersVideos::where('users_id' , $user->id)->get();    
            return response()->json( ( $result->count() ) ? $result : [] , 200 );
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }


    function getUserVideos( Request $request, $users_id )
    {
        $data = $request->json()->all();
        $result = UsersVideos::orderBy('id', 'DESC')->where( 'users_id' , $users_id )->get();    
        return response()->json( ( $result->count() ) ? $result : [] , 200 );
    }


    function getUserVideosByOwner( Request $request )
    {
        $data = $request->json()->all();
        $user =  User::where('api_token', $request->header('Authorization'))->first();

        if ($user && $request->header('Authorization') == $user['api_token']) 
        {
            $result = UsersVideos::orderBy('id', 'DESC')->where('users_id' , $user['id'])->get();    
            return response()->json( ( $result->count() ) ? $result : [] , 200 );
        }
        else
        {

            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }

    function getUserVideosByOwnerUnlocked( Request $request, $id)
    {
        $data = $request->json()->all();
        $user =  User::where('api_token', $request->header('Authorization'))->first();

        if ($user && $request->header('Authorization') == $user['api_token']) 
        {
            $result = UsersVideos::orderBy('id', 'DESC')->where('users_id' , $id)->get();    
            return response()->json( ( $result->count() ) ? $result : [] , 200 );
        }
        else
        {

            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }

    function deleteVideo( Request $request, $id )
    {
        if($id)
        {
            $data = $request->json()->all();
            $user = User::where('api_token', $request->header('Authorization'))->first();

            if($user)
            {
                $video = UsersVideos::find($id);

                if($video)
                {
                    $video->delete();
                    return response()->json( ['status'=>'delete'] , 200 , []);   
                }
                else
                {
                    return response()->json(['status'=>'Not found'] , 404 , []);
                }
            }
            else
            {
                return response()->json(['error'=>'Unauthorized'] , 401 , []);
            }
        }
        else
        {
            return response()->json(['error'=>'Length Required'] , 411 , []);  
        }
    }
}
