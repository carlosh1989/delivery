<?php

namespace App\Http\Controllers;

/* Models */
use App\Contacts;
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

class Contactscontroller extends Controller
{
    function addContact(Request $request)
    {
        $data = $request->json()->all();

        $user =  User::where('api_token', $request->header('Authorization'))->first();

        if($user)
        {
            if($data['contact'] AND $data['priority'])
            {
                $contact_update = Contacts::where('users_id',$user->id)->where('priority',$data['priority'])->update(['priority' => 'optional']);
                

                Contacts::create([
                    'users_id' => $user->id,
                    'contact' => $data['contact'],
                    'whatsapp' => $data['whatsapp'],
                    'priority' => $data['priority'],
                ]);

                return response()->json(['status' => 'Ok'],201);
            }
            else
            {
                return response()->json(['error'=>'Length Required'] , 411 , []);  
            }
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }

    function getContact(Request $request)
    {
        $data = $request->json()->all();

        $user =  User::where('api_token', $request->header('Authorization'))->first();

        if($user)
        {
            $contacts = Contacts::where('users_id',$user->id)->get();
            $contacts_exists = Contacts::where('users_id',$user->id)->first();
            
            $contact_primary = array();
        
            if($contacts_exists)
            {
                foreach ($contacts as $key => $contact) 
                {
                    if($contact->priority == 'primary')
                    {
                        $contacts_all[] = array(
                            'id' => $contact->id,
                            'users_id' => $contact->users_id,
                            'contact' => $contact->contact,
                            'whatsapp' => $contact->whatsapp,
                            'priority' => $contact->priority
                        );
                    }
                }

                foreach ($contacts as $key => $contact) 
                {
                    if($contact->priority == 'secondary')
                    {
                        $contacts_all[] = array(
                            'id' => $contact->id,
                            'users_id' => $contact->users_id,
                            'contact' => $contact->contact,
                            'whatsapp' => $contact->whatsapp,
                            'priority' => $contact->priority
                        );
                    }
                }

                foreach ($contacts as $key => $contact) 
                {
                    if($contact->priority == 'optional')
                    {
                        $contacts_all[] = array(
                            'id' => $contact->id,
                            'users_id' => $contact->users_id,
                            'contact' => $contact->contact,
                            'whatsapp' => $contact->whatsapp,
                            'priority' => $contact->priority
                        );
                    }
                }

                return $contacts_all;
            }
            else
            {
                return response()->json([], 200);
            }
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }

    function updateContact(Request $request, $id)
    {
        $data = $request->json()->all();

        $user =  User::where('api_token', $request->header('Authorization'))->first();

        if($user)
        {
            $contact_exists = Contacts::where('users_id',$user->id)->where('id',$id)->first();

            if($contact_exists)
            {
                $contact_update = Contacts::where('users_id',$user->id)->where('priority',$data['priority'])->update(['priority' => 'optional']);
                $contact = Contacts::where('users_id',$user->id)->where('id',$id)->update($data);
                $contact_updated = Contacts::where('users_id',$user->id)->where('id',$id)->first();
                return response()->json([$contact_updated],200);
            }
            else
            {
                return response()->json([], 200);   
            }
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }

    function updateWhatsapp(Request $request, $id)
    {
        $data = $request->json()->all();

        $user =  User::where('api_token', $request->header('Authorization'))->first();

        if($user)
        {
            $contact_exists = Contacts::where('users_id',$user->id)->where('id',$id)->first();

            if($contact_exists)
            {
                if($contact_exists->whatsapp == true)
                {
                    $contact_exists->whatsapp = 0;
                    $contact_exists->save();
                }
                else
                {
                    $contact_exists->whatsapp = 1;
                    $contact_exists->save();
                }

                return response()->json([$contact_exists],200);
            }
            else
            {
                return response()->json([], 200);   
            }
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }

    function deleteContact(Request $request, $id)
    {
        $data = $request->json()->all();

        $user =  User::where('api_token', $request->header('Authorization'))->first();

        if($user)
        {
            $contact_exists = Contacts::where('users_id',$user->id)->where('id',$id)->first();

            if($contact_exists)
            {
                $contact = Contacts::where('users_id',$user->id)->where('id',$id)->delete();
                return response()->json(['status'=>'Contact deleted!'],200);
            }
            else
            {
                return response()->json([], 200);   
            }
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }

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
