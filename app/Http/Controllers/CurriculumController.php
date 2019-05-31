<?php

namespace App\Http\Controllers;

/* Models */
use App\Curriculum;
use App\User;
use App\UsersVideos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class CurriculumController extends Controller
{
    function addCurriculum( Request $request )
    {
        //Verificar que es el propietario de la cuenta
        $data = $request->json()->all();
        extract($data);

        $user =  User::where('api_token', $request->header('Authorization'))->first();
        
        if ($user) 
        {
            $curriculum = Curriculum::create([
                'users_id' => $user->id,
                'section' => $data['section'],
                'year' => $data['year'],
                'title' => $data['title'],
                'role' => $data['role'],
                'director' => $data['director'],
                'country' => $data['country'],
                'productionHouse' => $data['productionHouse'],
                'create_at' => Carbon::now(),
                'updated_at'=> Carbon::now(),
            ]);  

            return response()->json(['status'=>'Curriculum Joined'],201);
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }

    function getCurriculum(Request $request)
    {
        $user =  User::where('api_token', $request->header('Authorization'))->first();

        if($user)
        {
            $curriculum = Curriculum::orderBy('id', 'DESC')->where('users_id', $user->id)->get(); 
            return response()->json($curriculum, 200);
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }

    function getCurriculumUnlocked(Request $request, $id)
    {
        $user =  User::where('api_token', $request->header('Authorization'))->first();

        if($user)
        {
            $curriculum = Curriculum::orderBy('id', 'DESC')->where('users_id', $id)->get(); 
            return response()->json($curriculum, 200);
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }

    function putCurriculum(Request $request, $id)
    {
        $user =  User::where('api_token', $request->header('Authorization'))->first();
        $data = $request->json()->all();
        $curriculum = Curriculum::find($id);

        if($user)
        {
            if($curriculum)
            {
                $curriculum->update($data);
                return response()->json(['status'=>'Curriculum updated'], 201);
            }
            else
            {
                return response()->json(['error','Dont exists that curriculum'] , 401 , []);
            }
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }

    function deleteCurriculum(Request $request, $id)
    {
        $user =  User::where('api_token', $request->header('Authorization'))->first();

        if($user)
        {
            if($id)
            {
                $curriculum = Curriculum::find($id);

                if($curriculum)
                {
                    $curriculum->delete();
                    return response()->json(['status' => 'Curriculum Deleted'],201);
                }
                else
                {
                    return response()->json(['error'=>'Dont Exists Curriculum'] , 400 , []);
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

        return $id;
    }
}
