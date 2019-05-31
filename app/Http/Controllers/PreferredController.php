<?php

namespace App\Http\Controllers;

/* Models */
use App\Register;
use App\User;
use App\UsersVideos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/* Views support*/
// use Illuminate\Support\Facades\View;

/* Mail support*/
// use \Swift_Mailer;
// use \Swift_SmtpTransport;
// use \Swift_Message;

class PreferredController extends Controller
{
    function addRolePreferred(Request $request)
    {
        if ($request->isJson()) 
        {
            $data = $request->json()->all();
            
            $user =  Register::where('api_token', $request->header('Authorization'))->first();
            
            if ($user) 
            {
                if($data['preferred_role'])
                {
                    $user->preferred_role = $data['preferred_role'];
                    $user->save();
                    return response()->json(['status => Preferred Role Add'] , 200 );
                }
                else
                {
                    return response()->json(['error'=>'Length Required'] , 411 , []);  
                }
            }
            else
            {
                return response()->json(['error'=>'Unauthorized'] , 401 , []);
            }
        }

        return response()->json(['error'=>'Bad Request'] , 400 , []);
    }

    function addProductionPreferred(Request $request)
    {
        if ($request->isJson()) 
        {
            $data = $request->json()->all();
            
            $user =  Register::where('api_token', $request->header('Authorization'))->first();
            
            if ($user) 
            {
                if($data['preferred_production'])
                {
                    $user->preferred_production = $data['preferred_production'];
                    $user->save();
                    return response()->json(['status => Preferred Production Add'] , 200 );
                }
                else
                {
                    return response()->json(['error'=>'Length Required'] , 411 , []);  
                }
            }
            else
            {
                return response()->json(['error'=>'Unauthorized'] , 401 , []);
            }
        }

        return response()->json(['error'=>'Bad Request'] , 400 , []);
    }

    function assingVideos( Request $request ){
        //Verificar que es el propietario de la cuenta

        if ($request->isJson()) 
        {
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
                return response()->json(['error'=>'Unauthorized'] , 401 , []);
            }
        }
        
        return response()->json(['error'=>'Bad Request'] , 400 , []);
    }


    function getUserVideos( Request $request, $users_id )
    {
        /* Validar el estatus del usuario */
        if ($request->isJson()) 
        {
                $data = $request->json()->all();
                $result = UsersVideos::where( 'users_id' , $users_id )->get();    
                return response()->json( ( $result->count() ) ? $result : [] , 200 );
        }
        else
        {
                return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }


    function getUserVideosByOwner( Request $request )
    {
        //Verificar que es el propietario de la cuenta
        if ($request->isJson()) 
        {

            $data = $request->json()->all();
            $user =  User::where('api_token', $request->header('Authorization'))->first();

            if ($user && $request->header('Authorization') == $user['api_token']) 
            {
                $result = UsersVideos::where('users_id' , $user['id'])->get();    
                return response()->json( ( $result->count() ) ? $result : [] , 200 );
            }
            else
            {

                return response()->json(['error','Unauthorized'] , 401 , []);
            }
        }

        return response()->json(['error','Unauthorized'] , 401 , []);
    }

    function deleteVideo( Request $request, $id )
    {
        if($request->isJson())
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
        return response()->json(['error','Bad Request'] , 400 , []);
    }
}
