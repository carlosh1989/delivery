<?php

namespace App\Http\Controllers;

/* Models */
use App\Questions;
use App\User;
use App\UsersAnswers;
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

class QuestionsController extends Controller
{
    public function getQuestions(Request $request)
    {
        $user =  User::where('api_token', $request->header('Authorization'))->first();

        if($user)
        {
            $questions = Questions::all();
            return $questions;
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }

    public function addUserAnswer(Request $request)
    {
        $data = $request->json()->all();
        $user =  User::where('api_token', $request->header('Authorization'))->first();

        if($user)
        {
            $answer_exists = UsersAnswers::where('questions_id',$data['questions_id'])
            ->where('users_id', $user->id)->first();

            if($answer_exists === null)
            {
                $answer = UsersAnswers::create([
                    'users_id' => $user->id,
                    'questions_id' => $data['questions_id'],
                    'answer' => $data['answer']
                ]);

                return response()->json(['status'=>'Ok'], 201);
            }
            else
            {
                return response()->json(['status'=>'Answer exist!'], 200);
            }
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }

    public function updateAnswer(Request $request)
    {
        $data = $request->json()->all();
        $user =  User::where('api_token', $request->header('Authorization'))->first();

        if($user)
        {
            $an = UsersAnswers::where('questions_id', $data['questions_id'])
            ->where('users_id', $user->id)
            ->first();

            if($an)
            {
                if($an->answer == 0)
                {
                    $an->answer = 1;
                    $an->save();
                }
                else
                {
                    $an->answer = 0;
                    $an->save();
                }
            }
            else
            {
                $answer = UsersAnswers::create([
                    'users_id' => $user->id,
                    'questions_id' => $data['questions_id'],
                    'answer' => 1
                ]);
            }
            
            return response()->json(['status'=> 'Answer updated'], 201);
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }

    public function getUserAnswer(Request $request)
    {
        $user =  User::where('api_token', $request->header('Authorization'))->first();

        if($user)
        {
            $answer = UsersAnswers::where('users_id',$user->id)
            ->where('id',$id)
            ->first();

            if($answer)
            {
                return response()->json($answer,200);
            }
            else
            {
                return response()->json([],200);
            }
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        } 
    }

    public function getUserAllAnswers(Request $request)
    {
        $user =  User::where('api_token', $request->header('Authorization'))->first();
        $questions = Questions::all();

        if($user)
        {
            $answers = array();

            foreach ($questions as $key => $question) 
            {
                $answer = UsersAnswers::where('users_id',$user->id)
                ->where('questions_id',$question->id)
                ->first();

                if($answer)
                {
                    $answers[] = array(
                        'id'       => $question->id,
                        'question' => $question->question,
                        'answer'   => $answer->answer
                    );
                }
                else
                {
                    $answers[] = array(
                        'id'       => $question->id,
                        'question' => $question->question,
                        'answer'   => 0,
                    );
                }
            }

            $question = array(
                'users_id' => $user->id,
                'answers' => $answers
            );

            return $question;
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        } 
    }

    public function getUserAllAnswersUnlocked(Request $request, $id)
    {
        $user =  User::where('api_token', $request->header('Authorization'))->first();
        $questions = Questions::all();

        if($user)
        {
            $answers = array();

            foreach ($questions as $key => $question) 
            {
                $answer = UsersAnswers::where('users_id',$id)
                ->where('questions_id',$question->id)
                ->first();

                if($answer)
                {
                    $answers[] = array(
                        'id'       => $question->id,
                        'question' => $question->question,
                        'answer'   => $answer->answer
                    );
                }
                else
                {
                    $answers[] = array(
                        'id'       => $question->id,
                        'question' => $question->question,
                        'answer'   => 0,
                    );
                }
            }

            $question = array(
                'users_id' => $user->id,
                'answers' => $answers
            );

            return $question;
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
