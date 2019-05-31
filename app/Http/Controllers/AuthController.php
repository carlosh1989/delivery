<?php

namespace App\Http\Controllers;

use App\CompanyUsers;
use App\User;
use Firebase\Auth\Token\Exception\InvalidToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use \Swift_Mailer;
use \Swift_Message;
use \Swift_SmtpTransport;

class AuthController extends Controller
{   
    public function register(Request $request)
    {
        $data = $request->json()->all();
        $register = new Register;

        if (!$register->userExist($data['email']))
        {
            $user = User::create([
                'name'      => $data['name'],
                'lastName'      => $data['lastName'],
                'email'     => $data['email'],
                'password'  => Hash::make($data['password']),
                'api_token' => str_random(60),
                'email_token' => str_random(60),
                'securityQuestion' => $data['securityQuestion'],
                'securityAnswer' => $data['securityAnswer'],
                'birthDate' => $data['birthDate'],
                'userType' => $data['userType'],
                'verified' => 0,
            ]);

            return response()->json(['status'=>'User Create!'],201);
        }
        else
        {
            return response()->json(['status'=> 'Usuario existe!'], 401);
        }
    }

    public function login(Request $request)
    {
        $data = $request->json()->all();
        $idTokenString = $data['token'];


        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/../../../secret/delivery-6056d-35650ad5765b.json');
 

        $firebase = (new Factory)->withServiceAccount($serviceAccount)->create();

        try {
            $verifiedIdToken = $firebase->getAuth()->verifyIdToken($idTokenString);
        } catch (InvalidToken $e) {
            echo $e->getMessage();
        }

        $uid = $verifiedIdToken->getClaim('sub');
        $user = $firebase->getAuth()->getUser($uid);

        $user_exists = User::where('email', $user->email)->first();
        $data = array();

        if($user_exists)
        {
            $company = CompanyUsers::where('user_id',$user_exists->id)
            ->where('enable_user',1)
            ->first();

            if($company)
            {
                /*
                $companies = CompanyUsers::where('user_id',$user_exists->id)
                ->where('enable_user',1)
                ->get();
                */
                $data['user_id'] = $user_exists->id;
                $data['userType'] = $user_exists->userType;
                $data['access_allowed'] = 1;

                return response()->json($data, 200);
            }
            else
            {
                $data['user_id'] = $user_exists->id;
                $data['userType'] = $user_exists->userType;
                $data['access_allowed'] = 0;

                return response()->json($data,200);
            }
        }
        else
        {
            $user = User::create([
                'name'      => $user->displayName,
                'lastName'      => $user->displayName,
                'email'     => $user->email,
                'password'  => '',
                'api_token' => str_random(60),
                'email_token' => str_random(60),
                'securityQuestion' => '',
                'securityAnswer' => '',
                'birthDate' => '',
                'userType' => 2,
                'verified' => 1,
            ]);

            return response()->json($user,201);
        }
    }

    public static function accessTokenVerify($request)
    {
        $data = $request->json()->all();
        $idTokenString = $data['token'];


        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/../../../secret/delivery-6056d-35650ad5765b.json');
 

        $firebase = (new Factory)->withServiceAccount($serviceAccount)->create();

        try {
            $verifiedIdToken = $firebase->getAuth()->verifyIdToken($idTokenString);
        } catch (InvalidToken $e) {
            echo $e->getMessage();
        }

        $uid = $verifiedIdToken->getClaim('sub');
        $user = $firebase->getAuth()->getUser($uid);

        $user_exists = User::where('email', $user->email)->first();

        if(!$user_exists)
        {
            return response()->json(['status'=>'Unauthorized!'],401);
        }
    }

    public function image(Request $request)
    {
        /*
        $data = $request->json()->all();

        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/../../../secret/delivery-6056d-35650ad5765b.json');

        $firebase = (new Factory)->withServiceAccount($serviceAccount)->create();

        $storage = $firebase->getStorage();
        // Get the default bucket
        $bucket = $storage->getBucket();

        // Get the bucket with the given name
        
        $bucket = $storage->getBucket(__DIR__.'/../../../secret/delivery-6056d-35650ad5765b.json');
        */
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/../../../systemdream2014-firebase-adminsdk-s609e-67ff32a0fe.json');


        $firebase = (new Factory)->withServiceAccount($serviceAccount)->create();

        $storage = $firebase->getStorage();

        $bucket = $storage->getBucket();

        foreach ($request->file('photos') as $photo)
        {   
            $newName = 'images/124/'.bin2hex(openssl_random_pseudo_bytes(16)).'.'.$photo->getClientOriginalExtension();

            $bucket->upload(
                file_get_contents($photo),
                [
                    'name' => $newName
                ]
            );

        }

        return response()->json(['status'=>'upload Ok!'], 201);
    }
    
    function index(Request $request){
        
        if ($request->isJson()) {
            $users = User::all();
            $users = DB::table('users')->paginate(15);
            return response()->json( $users, 200 );
        }
         
        return response()->json(['error','Unauthorized'] , 401 , []);
    }

    function testemail()
    {

                $user = User::select('id','email','name','lastName','email_token')
                    ->where('email','barinas.code@gmail.com')->first();

                    

                $content = View::make('mail.recover', ['user' => $user]);

            sendEmail(
                    [
                        'authWith' => 'info@posteayvende.com',
                        'setSubject' => 'Account activation',
                        'setFrom' => ['support@rdcasting.com', 'Info'],
                        'emailbody' => $content->render(),
                        'to' => 'elmorochez22@gmail.com'
                        
                    ]
                );



            return response()->json(['status' => 'Email sended!'], 200);
    }

    function verifyRecoverAccount(Request $request)
    {
        if ($request->isJson()) 
        {
            $data = $request->json()->all();

            if($data['email'] && $data['email_token'])
            {
                $user = User::select('id','email','email_token')
                        ->where('email',$data['email'])
                        ->where('email_token', $data['email_token'])
                        ->first();

                if($user)
                {
                    return response()->json( $user , 200 );
                }
                else
                {
                    return response()->json(['error','Unauthorized'] , 401 , []); 
                }
            }
            else
            {
                return response()->json(['error','Length Required'] , 411 , []);  
            }
        
            return response()->json(['error','Bad Request'] , 400 , []);
        }
    }


    function sendRecoverAccount(Request $request){

        if ($request->isJson()) {
        
            $data = $request->json()->all();
            $register = new Register;

            if ($register->userExist($data['email']))
            {
                
                User::where('email', $data['email'])
                    ->update(
                    ['email_token' => str_random(60)]
                );
                

                $user = User::select('id','email','name','lastName','email_token')
                    ->where('email',$data['email'])
                    ->where('verified', 1)
                    ->first();

                    

                $content = View::make('mail.recover', ['user' => $user]);

            sendEmail(
                    [
                        'authWith' => 'info@posteayvende.com',
                        'setSubject' => 'Account activation',
                        'setFrom' => ['info@posteayvende.com', 'Info'],
                        'emailbody' => $content->render(),
                        'to' => $user['email']
                        
                    ]
                );
            
            }
            else
            {
                return response()->json(['error','Unauthorized'] , 401 , []);
            }


           


            return response()->json( [] , 201 );
        }
         
        return response()->json(['error','Unauthorized'] , 401 , []);

    }



    function recoverAttemp(Request $request){

        if ($request->isJson()) {
        
            $data = $request->json()->all();
            $register = new Register;

            // Si el usuario existe
            if ($register->userExist($data['email']))
            {   
                // Si las claves son verdaderas
                if ($data['password'] == $data['password2'])
                {
                    
                    $user = User::where('email_token', $data['email_token'])
                        ->where('email', $data['email'])
                        ->get()
                        ->first();

                    if ($user['id'])
                    {   
                        
                        User::where('email', $user['email'])
                            ->where('email_token', $data['email_token'] )
                            ->update(
                                [
                                    'password'  => Hash::make($data['password']),
                                    'email_token' => str_random(60)
                                ]
                        );

                        return response()->json( $user , 201 );
                    }
                    else
                    {
                        return response()->json(['error','The token to recover the account is invalid'] , 401 , []);     
                    }
                }
                else
                {
                   return response()->json(['error','The confirmation key does not match'] , 401 , []);
                   
                }

                    
            }
            else
            {
                return response()->json(['error','Unauthorized'] , 401 , []);
            }


           


            return response()->json( $user , 201 );
        }
         
        return response()->json(['error','Unauthorized'] , 401 , []);

    }




    function registerTalent(Request $request){

        if ($request->isJson()) {
        
            $data = $request->json()->all();
            $register = new Register;

            if($data['userType'] == 1 OR $data['userType'] == 2)
            {
                if (!$register->userExist($data['email']))
                    {
                        
                        $user = User::create([
                            'name'      => $data['name'],
                            'lastName'      => $data['lastName'],
                            'email'     => $data['email'],
                            'password'  => Hash::make($data['password']),
                            'api_token' => str_random(60),
                            'email_token' => str_random(60),
                            'securityQuestion' => $data['securityQuestion'],
                            'securityAnswer' => $data['securityAnswer'],
                            'birthDate' => $data['birthDate'],
                            'userType' => $data['userType'],
                            'verified' => 0,
                        ]);

                        if($data['primaryPhone'])
                        {
                            Contacts::create([
                                'users_id' => $user->id,
                                'contact' => $data['primaryPhone'],
                                'whatsapp' => 0,
                                'priority' => 'primary',
                            ]);
                        }

                        if($data['secondaryPhone'])
                        {
                            Contacts::create([
                                'users_id' => $user->id,
                                'contact' => $data['secondaryPhone'],
                                'whatsapp' => 0,
                                'priority' => 'secondary',
                            ]);
                        }
                }
                else
                {
                    return response()->json(['error','User already exists'] , 401 , []);
                }


                $content = View::make('mail.activation', ['user' => $user]);
                
                
                sendEmail(
                        [
                            'authWith' => 'info@posteayvende.com',
                            'setSubject' => 'Account activation',
                            'setFrom' => ['info@posteayvende.com', 'Info'],
                            'emailbody' => $content->render(),
                            'to' => $user['email']
                            
                        ]
                    );

                
                return response()->json( $user , 201 );
            }
            else
            {
                return response()->json(['status'=>'Incorrect data'], 401);
            }
        }
         
        return response()->json(['error','Unauthorized'] , 401 , []);
    }



    function activateAccount(Request $request,$id,$email_token){

        
            try {
                
                    $user = User::where('email_token',$email_token)
                    ->where('verified', 0 )
                    ->find($id);
                    
                    if ($user['email_token'] == $email_token && $user['id'] == $id)
                    {   

                        User::select('id','email','name','lastName','userType','api_token','password','verified')->where('id', $user['id'])
                            ->where('email_token', $email_token )
                            ->update(
                                ['verified' => 1]
                        );



                        return response()->json($user,201);
                    }
                    else{
                       return response()->json(['error' => 'Incorrect token or the account has already been activated', 401]);
                    }
            } catch (Exception $e) {
                
                   return response()->json(['error' => 'Incorrect token or the account has already been activated', 401]);
            }

    }



    function getToken(Request $request){

        if ($request->isJson()) {
            try {
                
                $data = $request->json()->all();
                
              if ($data['email'] && $data['password']) {
                  # code...
                $user = User::select('id','email','name','lastName','userType','api_token','password','verified')
                    ->where('email',$data['email'])
                    ->where('verified', 1)
                    ->first();
                
                if ($user && Hash::check($data['password'], $user['password']))
                {
                    return response()->json($user,200);
                }
                else{

                   return response()->json(['error' => 'Incorrect password or email', 401],401);
                }
              }else{
                return response()->json(['error' => 'Missing parameters to complete the authentication', 401],401);
              }

            } catch (Exception $e) {
                
                   return response()->json(['error' => 'No content', 406]);
            }
        }

    }

    function logout(Request $request)
    {

        if ($request->isJson()) {
            try {

               $data = $request->json()->all();
                

               $user =  User::where('id', $data['users_id'])
               ->where('api_token', $request->header('Authorization'))->first();

               // Verificar si el usuario existe
                if ($user['api_token'] == $request->header('Authorization') )
                {   

                    User::where('id',$data['users_id'])
                    ->where('api_token', $request->header('Authorization') )
                    ->update(
                        ['api_token' => str_random(60)]
                    );

                    return response()->json([],200);
                }
                else
                {
                   return response()->json(['error' => 'No content', 406]);
                }
            } catch (Exception $e) {
                
                   return response()->json(['error' => 'No content', 406]);
            }
        }



    }


    function sendEmail(){

        $content = View::make('mail.example', ['name' => 'Rishabh']);
        sendEmail(
                [
                    'authWith' => 'info@posteayvende.com',
                    'setSubject' => 'Title Email',
                    'setFrom' => ['info@posteayvende.com', 'Leonardo Tapia'],
                    'emailbody' => $content->render(),
                    'to' => 'barinas.code@gmail.com'
                ]
            );
    }


}
