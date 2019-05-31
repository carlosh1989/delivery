<?php

namespace App\Http\Controllers;

use App\User;
use App\ProfileGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use \Swift_Mailer;
use \Swift_SmtpTransport;
use \Swift_Message;

class ProfileGalleryController extends Controller
{
    function getMyGallery(Request $request)
    {         
        $user =  User::where('api_token', $request->header('Authorization'))->first();
        
        if ($request->header('Authorization') == $user['api_token']) {
            
            $results = ProfileGallery::orderBy('id', 'DESC')->where('users_id',$user['id'])->get();
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

    function getMyGalleryUnlocked(Request $request, $id)
    {                 
        $user =  User::where('api_token', $request->header('Authorization'))->first();

        if ($user) {
            
            $results = ProfileGallery::orderBy('id', 'DESC')->where('users_id',$id)->get();
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

    function createGallery(Request $request){
        
         // -- 
         //Publishable by the owner of the account if is talent
        
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
                $path = 'uploads/profile-gallery/'.$user['id'];
                
                // Recorrer el array de fotos
                foreach ($request->file('photos') as $photo)
                {   
                    $newName = bin2hex(openssl_random_pseudo_bytes(16)).'.'.$photo->getClientOriginalExtension();
                    // Guardar las fotos en la carpeta
                    $photo->move($path,$newName);
                    
                    // Guardar los nombres de las fotos en la tabla
                    $profileGallery = ProfileGallery::create([
                        'users_id'   => $user['id'], 
                        'filename' =>  $path.'/'.$newName
                    ]);
                }

                return response()->json( ['status'=>'Created','data'=>$profileGallery] , 201 );
            }
            else
            {
                return response()->json(['error'=>'Length Required'] , 411 , []); 
            }
    }

    public function deleteGallery(Request $request, $id)
    {
        $user =  User::where('api_token', $request->header('Authorization'))->first();
        
        if ($request->header('Authorization') == $user['api_token']) 
        {
            $results = ProfileGallery::where('users_id',$user['id'])
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
