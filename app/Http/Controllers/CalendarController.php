<?php

namespace App\Http\Controllers;

use App\User;
use App\UsersCalendar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CalendarController extends Controller
{
    /**
     * Retrieve the user for the given ID.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request)
    {
        $data = $request->json()->all();
        $user = User::where('api_token', $request->header('Authorization'))->first();

        if($user)
        {
            $calendar = UsersCalendar::where('users_id',$user->id)->get();

            if($calendar)
            {
                return response()->json( ['status'=>'ok', 'data'=>$calendar] , 200);   
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

    public function searchDate(Request $request)
    {
        if ($request->isJson()) 
        {
            $data = $request->json()->all();
            $user = User::where('api_token', $request->header('Authorization'))->first();

            if($user)
            {
                $users_id = $data['users_id'];
                $date = $data['date'];

                if($date and $users_id)
                {
                    $calendar = UsersCalendar::where('users_id', $users_id)
                    ->where('date', 'like', '%' . $date . '%')->first();

                    if($calendar)
                    {
                        return response()->json( ['status'=>'ok', 'data'=>$calendar] , 200);   
                    }
                    else
                    {
                        return response()->json(['status'=>'Not found'] , 404 , []);
                    }
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

        return response()->json(['error','Bad Request'] , 400 , []);
    }

    public function create( Request $request )
    {
        if ($request->isJson()) 
        {
            $data = $request->json()->all();
            
            $user =  User::where('api_token', $request->header('Authorization'))->first();
            
            if ($user) 
            {
                $title = $data['title'];

                if($title)
                {
                    UsersCalendar::create([
                        'users_id' => $user->id,
                        'title' => $data['title'],
                        'date' => Carbon::now(),
                        'status' => 1
                    ]);

                    $calendar  = UsersCalendar::where('users_id' , $user->id)->get();    
                    return response()->json( ['status'=>'ok', 'data'=>$calendar] , 200);   
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
        
        return response()->json(['error','Bad Request'] , 400 , []);
    }   

    public function delete(Request $request, $id)
    {
        $data = $request->json()->all();
        
        $user =  User::where('api_token', $request->header('Authorization'))->first();
        
        if ($user) 
        {
            if($id)
            {
                $calendar = UsersCalendar::where('date',$id)
                ->where('users_id',$user->id)
                ->where('status',2)
                ->first();

                if($calendar)
                {
                    $calendar->delete();
                    return response()->json([
                        'id_post'=>$calendar->users_bussiness_proposal_id,
                        'action' => 'enabled'
                    ], 200);  
                }
                else
                {
                    $calendar_exists = UsersCalendar::where('users_id',$user->id)
                    ->where('status',1)
                    ->where('date',$id)
                    ->first();

                    if($calendar_exists)
                    {
                        return response()->json([
                            'id_post'=>$calendar_exists->users_bussiness_proposal_id,
                            'action' => 'redirect'
                        ], 200);
                    }
                    else
                    {
                        UsersCalendar::create([
                            'users_id' => $user->id,
                            'title' => 'disabled date',
                            'date' => $id,
                            'status' => 2
                        ]);
   
                        return response()->json([
                            'id_post'=>0,
                            'action' => 'disabled'
                        ], 201);
                    }
                }
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

    public function addDates(Request $request)
    {
        $user = User::where('api_token', $request->header('Authorization'))->first();

        if($user)
        {
            $dates = $request->input('dates');
            $title = $request->input('title');

            if($dates)
            {
                foreach ($dates as $key => $date) 
                {
                    UsersCalendar::create([
                        'users_id' => $user->id,
                        'title' => $title,
                        'date' => $date,
                        'status' => 1
                    ]);

                    return response()->json(['status'=>'Dates joined!'], 201);
                }
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