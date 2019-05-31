<?php

namespace App\Http\Controllers;

/* Models */
use App\User;
use App\UsersBussinessProposal;
use App\UsersFeaturesDetail;
use App\FeaturesLabels;
use App\FeaturesValues;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/* Views support*/
use Illuminate\Support\Facades\View;

/* Mail support*/
use \Swift_Mailer;
use \Swift_SmtpTransport;
use \Swift_Message;

class UsersController extends Controller
{
    
    function index(Request $request){
        
        if ($request->isJson()) {
            $users = User::all();
            $users = DB::table('users')->paginate(15);
            return response()->json( $users, 200 );
        }
         
        return response()->json(['error','Unauthorized'] , 401 , []);
    }


    function assingFeatures( Request $request , $id_user ){
        

        //Verificar que es el propietario de la cuenta

        if ($request->isJson()) {
            
            $data = $request->json()->all();
            $featuresList = $data['feature_values'];

            $uf = new UsersFeaturesDetail;

                // Deleting old rows by label feature

                $uf->where('users_id','=',$id_user)
                ->join('features_values', 'features_values.id', '=', 'users_features_detail.features_values_id')
                ->join('features_labels', 'features_labels.id', '=', 'features_values.features_labels_id')
                ->where('features_labels_id','=', $data['features_labels_id'])
                ->delete();



            foreach($featuresList as $f )
            {   
                
                

                UsersFeaturesDetail::create([
                    'users_id' => $id_user,
                    'features_values_id' => $f
                ]);

            }

            return response()->json([],201);
        }
         
        return response()->json(['error','Unauthorized menol'] , 401 , []);
    }


        function getUserFeatures( Request $request, $id_user ){
        

        //Verificar que es el propietario de la cuenta

            $user =  User::where('id', $id_user)
            ->where('api_token', $request->header('Authorization'))->first();
            
            if ($user && $request->header('Authorization') == $user['api_token']) {
                
                
               $features = FeaturesLabels::
                select('id','features_categories_id', 'featureLabel')
                    ->with(array('features_values' => function($query) use ($id_user){
                        
                        $query->select('features_values.id','features_labels_id','featureValue', 'users_id')
                            ->rightjoin('users_features_detail', 'users_features_detail.features_values_id', '=', 'features_values.id' )
                            ->where('users_features_detail.users_id','=', $id_user);
                        
                    }))
                ->whereHas('features_values', function ($query) use ($id_user) {
                    $query->select('features_values.id','features_labels_id','featureValue', 'users_id')
                            ->rightjoin('users_features_detail', 'users_features_detail.features_values_id', '=', 'features_values.id' )
                            ->where('users_features_detail.users_id','=', $id_user);
                })->get();
            
            return response()->json( ($features->count()) ? $features : [] , 200 );

            }
            else
            {

                return response()->json(['error','Unauthorized'] , 401 , []);
            }

            
           
        }

        function getUserFeaturesUnlocked( Request $request, $id_user ){
        

        //Verificar que es el propietario de la cuenta

            $user =  User::where('id', $id_user)
            ->where('api_token', $request->header('Authorization'))->first();
            
            if ($user && $request->header('Authorization') == $user['api_token']) {
                
                
               $features = FeaturesLabels::
                select('id','features_categories_id', 'featureLabel')
                    ->with(array('features_values' => function($query) use ($id_user){
                        
                        $query->select('features_values.id','features_labels_id','featureValue', 'users_id')
                            ->rightjoin('users_features_detail', 'users_features_detail.features_values_id', '=', 'features_values.id' )
                            ->where('users_features_detail.users_id','=', $id_user);
                        
                    }))
                ->whereHas('features_values', function ($query) use ($id_user) {
                    $query->select('features_values.id','features_labels_id','featureValue', 'users_id')
                            ->rightjoin('users_features_detail', 'users_features_detail.features_values_id', '=', 'features_values.id' )
                            ->where('users_features_detail.users_id','=', $id_user);
                })->get();
            
            return response()->json( ($features->count()) ? $features : [] , 200 );

            }
            else
            {

                return response()->json(['error','Unauthorized'] , 401 , []);
            }

            
           
        }

        function getUserFeaturesTalents( Request $request, $id_user ){
        

        //Verificar que es el propietario de la cuenta

            $user =  User::where('id', $id_user)
            ->where('api_token', $request->header('Authorization'))->first();
            
            if ($user && $request->header('Authorization') == $user['api_token']) {
                
                
               $features = FeaturesLabels::
                select('id','features_categories_id', 'featureLabel')
                    ->with(array('features_values' => function($query) use ($id_user){
                        
                        $query->select('features_values.id','features_labels_id','featureValue', 'users_id')
                            ->rightjoin('users_features_detail', 'users_features_detail.features_values_id', '=', 'features_values.id' )
                            ->where('users_features_detail.users_id','=', $id_user);
                        
                    }))
                ->whereHas('features_values', function ($query) use ($id_user) {
                    $query->select('features_values.id','features_labels_id','featureValue', 'users_id')
                            ->rightjoin('users_features_detail', 'users_features_detail.features_values_id', '=', 'features_values.id' )
                            ->where('users_features_detail.users_id','=', $id_user);
                })
                ->where('features_categories_id',2)
                ->get();
            
            return response()->json( ($features->count()) ? $features : [] , 200 );

            }
            else
            {

                return response()->json(['error','Unauthorized'] , 401 , []);
            }
        }

        function getUserFeaturesTalentsUnlocked( Request $request, $id_user ){
        

        //Verificar que es el propietario de la cuenta

        $user =  User::where('api_token', $request->header('Authorization'))->first();
             
        if($user){   
                
               $features = FeaturesLabels::
                select('id','features_categories_id', 'featureLabel')
                    ->with(array('features_values' => function($query) use ($id_user){
                        
                        $query->select('features_values.id','features_labels_id','featureValue', 'users_id')
                            ->rightjoin('users_features_detail', 'users_features_detail.features_values_id', '=', 'features_values.id' )
                            ->where('users_features_detail.users_id','=', $id_user);
                        
                    }))
                ->whereHas('features_values', function ($query) use ($id_user) {
                    $query->select('features_values.id','features_labels_id','featureValue', 'users_id')
                            ->rightjoin('users_features_detail', 'users_features_detail.features_values_id', '=', 'features_values.id' )
                            ->where('users_features_detail.users_id','=', $id_user);
                })
                ->where('features_categories_id',2)
                ->get();
            
            return response()->json( ($features->count()) ? $features : [] , 200 );

            }
            else
            {

                return response()->json(['error','Unauthorized'] , 401 , []);
            }
        }


        function getUserFeaturesPhysicals( Request $request, $id_user ){
        

        //Verificar que es el propietario de la cuenta

            $user =  User::where('id', $id_user)
            ->where('api_token', $request->header('Authorization'))->first();
            
            if ($user && $request->header('Authorization') == $user['api_token']) {
                
                
               $features = FeaturesLabels::
                select('id','features_categories_id', 'featureLabel')
                    ->with(array('features_values' => function($query) use ($id_user){
                        
                        $query->select('features_values.id','features_labels_id','featureValue', 'users_id')
                            ->rightjoin('users_features_detail', 'users_features_detail.features_values_id', '=', 'features_values.id' )
                            ->where('users_features_detail.users_id','=', $id_user);
                        
                    }))
                ->whereHas('features_values', function ($query) use ($id_user) {
                    $query->select('features_values.id','features_labels_id','featureValue', 'users_id')
                            ->rightjoin('users_features_detail', 'users_features_detail.features_values_id', '=', 'features_values.id' )
                            ->where('users_features_detail.users_id','=', $id_user);
                })
                ->where('features_categories_id',3)
                ->get();
            
            return response()->json( ($features->count()) ? $features : [] , 200 );

            }
            else
            {

                return response()->json(['error','Unauthorized'] , 401 , []);
            }
        }

        function getUserFeaturesPhysicalsUnlocked( Request $request, $id_user ){
        

        //Verificar que es el propietario de la cuenta

        $user =  User::where('api_token', $request->header('Authorization'))->first();
             
        if($user){  
            
               $features = FeaturesLabels::
                select('id','features_categories_id', 'featureLabel')
                    ->with(array('features_values' => function($query) use ($id_user){
                        
                        $query->select('features_values.id','features_labels_id','featureValue', 'users_id')
                            ->rightjoin('users_features_detail', 'users_features_detail.features_values_id', '=', 'features_values.id' )
                            ->where('users_features_detail.users_id','=', $id_user);
                        
                    }))
                ->whereHas('features_values', function ($query) use ($id_user) {
                    $query->select('features_values.id','features_labels_id','featureValue', 'users_id')
                            ->rightjoin('users_features_detail', 'users_features_detail.features_values_id', '=', 'features_values.id' )
                            ->where('users_features_detail.users_id','=', $id_user);
                })
                ->where('features_categories_id',3)
                ->get();
            
            return response()->json( ($features->count()) ? $features : [] , 200 );

            }
            else
            {

                return response()->json(['error','Unauthorized'] , 401 , []);
            }
        }

        function getUserSession(Request $request)
        {
            $user =  User::where('api_token', $request->header('Authorization'))->first();
            return $user;
        }

        function userViews(Request $request, $id)
        {
            $user =  User::where('api_token', $request->header('Authorization'))->first();

            if($user)
            {
                $talent = User::where('id',$id)->first();

                $views = $talent->views + 1;
                $talent->views = $views;
                $talent->save();

                $talent_views = $talent->views;

                return response()->json($talent_views, 200);
            }
            else
            {
                return response()->json(['error','Unauthorized'] , 401 , []);
            }

            return $user;
        }

        function getUserSessionUnlocked(Request $request, $id)
        {
            $user =  User::where('api_token', $request->header('Authorization'))->first();

            if($user)
            {
                $user_data = User::find($id);

                if($user_data)
                {
                    $data = array(
                        'id' => $user_data->id,
                        'profilePhoto' => $user_data->profilePhoto,
                    );
                    return response()->json($data, 200);
                }
                else
                {
                    return response()->json(['status'=>'Not found!'],200);
                }
            }
            else
            {
                return response()->json(['error','Unauthorized'] , 401 , []);
            }
        }
}
