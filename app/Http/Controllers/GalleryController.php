<?php

namespace App\Http\Controllers;

/* Models */
use App\MyMoment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use \Swift_Mailer;
use \Swift_Message;
use \Swift_SmtpTransport;

class GalleryController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('photos'))
        {   
            // Carpeta donde se guardaran las fotos
            $path = 'uploads/profile-my-moment/'.$user['id'];
            
            // Recorrer el array de fotos
            foreach ($request->file('photos') as $photo)
            {   
                $newName = bin2hex(openssl_random_pseudo_bytes(16)).'.'.$photo->getClientOriginalExtension();
                
                // Guardar las fotos en la carpeta
                $photo->move($path,$newName);

                
                // Guardar los nombres de las fotos en la tabla
                $myMoment = MyMoment::create([
                    'users_id'   => $user['id'], 
                    'filename'   =>  $path.'/'.$newName
                ]);

            }
            
            return response()->json( ['status'=>'Created','data'=> $myMoment] , 201 );
        }
        else
        {
            return response()->json(['error'=>'Length Required'] , 411 , []);  
        }
    }

    function getMyMoment(Request $request)
    {  
        $user =  User::where('api_token', $request->header('Authorization'))->first();
        
        if ($request->header('Authorization') == $user['api_token']) {
            
            $results = MyMoment::orderBy('id', 'DESC')->where('users_id',$user['id'])->get();
            $images = array();

            if($results)
            {
                foreach ($results as $key => $result) 
                {
                    $images[] = array(
                        'id' => $result->id,
                        'src' => url($result->filename),
                        'thumbnail' => url($result->filename),
                        'thumbnailWidth' => 200,
                        'thumbnailHeight' => 200
                    );
                }

                return response()->json( $images, 200 );
            }
            else
            {
                return response()->json([], 200 );
            }
        }
        else
        {
            return response()->json(['error'=>'Unauthorized'] , 401 , []);
        }
    }

    function getMyMomentUnlocked(Request $request, $id)
    {  
        $user =  User::where('api_token', $request->header('Authorization'))->first();
        
        if ($request->header('Authorization') == $user['api_token']) {
            
            $results = MyMoment::orderBy('id', 'DESC')->where('users_id',$id)->get();
            $images = array();

            if($results)
            {
                foreach ($results as $key => $result) 
                {
                    $images[] = array(
                        'id' => $result->id,
                        'src' => url($result->filename),
                        'thumbnail' => url($result->filename),
                        'thumbnailWidth' => 200,
                        'thumbnailHeight' => 200
                    );
                }

                return response()->json( $images, 200 );
            }
            else
            {
                return response()->json([], 200 );
            }
        }
        else
        {
            return response()->json(['error'=>'Unauthorized'] , 401 , []);
        }
    }


    function createGallery(Request $request)
    {
        $user =  User::where('api_token', $request->header('Authorization'))
                ->where('userType', 1 ) // Client
                ->first();
        
        if (!$user)
        {
            return response()->json(['error','You are not a talent'] , 401 , []);
        }

        // --
        // Continue . . .
        // Guardar los archivos en la galeria
        if ($request->hasFile('photos'))
        {   
            // Carpeta donde se guardaran las fotos
            $path = 'uploads/profile-my-moment/'.$user['id'];
            
            // Recorrer el array de fotos
            foreach ($request->file('photos') as $photo)
            {   
                $newName = bin2hex(openssl_random_pseudo_bytes(16)).'.'.$photo->getClientOriginalExtension();
                
                // Guardar las fotos en la carpeta
                $photo->move($path,$newName);

                
                // Guardar los nombres de las fotos en la tabla
                $myMoment = MyMoment::create([
                    'users_id'   => $user['id'], 
                    'filename'   =>  $path.'/'.$newName
                ]);

            }
            
            return response()->json( ['status'=>'Created','data'=> $myMoment] , 201 );
        }
        else
        {
            return response()->json(['error'=>'Length Required'] , 411 , []);  
        }
    }

    public function deleteMyMoment(Request $request, $id)
    {
        $user =  User::where('api_token', $request->header('Authorization'))->first();
        
        if ($request->header('Authorization') == $user['api_token']) 
        {
            $results = MyMoment::where('users_id',$user['id'])
            ->where('id',$id)
            ->first();

            if($results)
            {
                File::delete($results->filename);
                $results->delete();
                return response()->json([
                    'status'=>'Deleted',
                    'id' => $id
                ], 200);
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
}
