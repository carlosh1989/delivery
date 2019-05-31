<?php

/* Models */

namespace App\Http\Controllers;
use App\RecipientsBussinessProposal;
use App\User;
use App\UsersBussinessProposal;
use App\UsersCalendar;
use App\UsersVideos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/* Views support*/
// use Illuminate\Support\Facades\View;

/* Mail support*/
// use \Swift_Mailer;
// use \Swift_SmtpTransport;
// use \Swift_Message;

class TalentController extends Controller
{
    function getTalentsProposals(Request $request)
    {
        Carbon::setLocale('es');

        if ($request->isJson()) 
        {
            $data = $request->json()->all();
            
            $user =  User::where('api_token', $request->header('Authorization'))->first();
            
            if ($user) 
            {
                $proposalsRecipients = RecipientsBussinessProposal::where('recipient',$user->id)
                ->get();

                if($proposalsRecipients->count())
                {
                    foreach ($proposalsRecipients as $key => $proposalsRecipient) 
                    {
                        $proposalTalents = UsersBussinessProposal::where('id', $proposalsRecipient->users_bussiness_proposal_id)->get();

                        foreach ($proposalTalents as $key => $proposalTalent) 
                        {
                            $proposals[] = array(
                                'id' => $proposalTalent->id,
                                'title' => $proposalTalent->title,
                                'description' => $proposalTalent->description,
                                'created_at' => $proposalTalent->created_at->diffForHumans(),
                            );
                        }

                    }

                    return response()->json( $proposals , 200 );
                }
                else
                {
                    return response()->json( [] , 200 );      
                }
            }
            else
            {
                return response()->json(['error','Unauthorized'] , 401 , []);
            }
        }
        
        return response()->json(['error','Bad Request'] , 400 , []); 
    }

    function goals(Request $request)
    {
        return 'lorem ipsum';
    }

    function changeStatus(Request $request)
    {
        if ($request->isJson()) 
        {
            $data = $request->json()->all();
            
            $user =  User::where('api_token', $request->header('Authorization'))->first();
            
            if ($user) 
            {
                $proposalsRecipients = RecipientsBussinessProposal::where('recipient',$user->id)
                ->where('users_bussiness_proposal_id',$data['proposal_id'])
                ->first();

                //$proposalsRecipients->accepted = $data['status'];
                //$proposalsRecipients->save();
                //return $proposalsRecipients->recipient;
                //return response()->json( ['status'=>'Status updated'] , 200 );
                $recipient = RecipientsBussinessProposal::where('recipient',$user->id)->where('users_bussiness_proposal_id',$data['proposal_id'])->first();
                $recipient->accepted = $data['status'];
                return $recipient;
            }
            else
            {
                return response()->json(['error','Unauthorized'] , 401 , []);
            }
        }
        
        return response()->json(['error','Bad Request'] , 400 , []); 
    }

    function Talentapprovate(Request $request, $id)
    {
        $data = $request->json()->all();
        
        $user =  User::where('api_token', $request->header('Authorization'))->first();
        
        if ($user) 
        {
            $proposalsRecipients = RecipientsBussinessProposal::where('recipient',$user->id)
            ->where('users_bussiness_proposal_id',$id)
            ->first();

            $proposal = UsersBussinessProposal::find($proposalsRecipients->users_bussiness_proposal_id);

            if($proposalsRecipients)
            {
                //agregando fecha a talento del casting
                UsersCalendar::create([
                    'users_id' => $user->id,
                    'title'    => 'disabled date',
                    'date'     => $proposal->filmingDate,
                    'status'   => 1,
                    'users_bussiness_proposal_id' => $proposal->id
                ]);

                $proposalsRecipients->proposal_estatus = $data['estatus'];
                $proposalsRecipients->save();
            }
            else
            {
                return response()->json(['status' => 'Not Found'], 401);
            }

            return response()->json($proposalsRecipients, 201);
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }

    function Talentrenegotiate(Request $request, $id)
    {
        $data = $request->json()->all();
        
        $user =  User::where('api_token', $request->header('Authorization'))->first();
        
        if ($user) 
        {
            $proposalsRecipients = RecipientsBussinessProposal::where('recipient',$user->id)
            ->where('users_bussiness_proposal_id',$id)
            ->first();

            if($proposalsRecipients)
            {
                $proposalsRecipients->proposal_estatus = $data['estatus'];
                $proposalsRecipients->renegotiatedPrice = $data['renegotiatedPrice'];
                $proposalsRecipients->save();
            }
            else
            {
                return response()->json(['status' => 'Not Found'], 401);
            }

            return response()->json($proposalsRecipients, 201);
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }

    function TalentDelete(Request $request, $id)
    {
        $data = $request->json()->all();
        
        $user =  User::where('api_token', $request->header('Authorization'))->first();
        
        if ($user) 
        {
            $proposalsRecipients = RecipientsBussinessProposal::find($id);

            if($proposalsRecipients)
            {
                $proposalsRecipients->delete();
            }
            else
            {
                return response()->json(['status' => 'Not Found'], 401);
            }

            return response()->json(['stats'=>'Recipient Proposal Deleted!'], 200);
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }


    function TalentDeclinate(Request $request, $id)
    {
        $data = $request->json()->all();
        
        $user =  User::where('api_token', $request->header('Authorization'))->first();
        
        if ($user) 
        {
            $proposalsRecipients = RecipientsBussinessProposal::where('users_bussiness_proposal_id',$id)->where('recipient',$user->id)->first();

            if($proposalsRecipients)
            {
                if ($proposalsRecipients->proposal_estatus == 0) 
                {
                    $proposalsRecipients->proposal_estatus = 5;
                    $proposalsRecipients->save();
                    //$proposalsRecipients->delete();
                } 
                else 
                {
                    return response()->json(['status'=>'Denegado'],401);
                }
            }
            else
            {
                return response()->json(['status' => 'Not Found'], 404);
            }

            return response()->json(['stats'=>'Recipient Proposal Declinate!'], 200);
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }

    function clientRenegotiate(Request $request)
    {
        $data = $request->json()->all();
        
        $user =  User::where('api_token', $request->header('Authorization'))->first();
        $proposal = UsersBussinessProposal::find($data['proposal_id']);
        
        if ($user AND $proposal->sender == $user->id) 
        {   

            $proposalsRecipients = RecipientsBussinessProposal::where('users_bussiness_proposal_id',$data['proposal_id'])->where('recipient', $data['recipient'])->first();


            if($proposalsRecipients)
            {
                if($proposalsRecipients->proposal_estatus == 2)
                {
                    if($data['estatus'] == 4)
                    {
                        $proposalsRecipients->delete();
                        return response()->json(['status'=>'Success!'],200);
                    }
                    else
                    {
                        //agregando fecha a talento del casting
                        UsersCalendar::create([
                            'users_id' => $data['recipient'],
                            'title'    => 'disabled date',
                            'date'     => $proposal->filmingDate,
                            'status'   => 1,
                            'users_bussiness_proposal_id' => $proposal->id
                        ]);

                        $proposalsRecipients->proposal_estatus = $data['estatus'];
                        $proposalsRecipients->save();
                        return response()->json(['status'=>'Updated Recipient Proposal!'], 200);
                    }
                }
                else
                {
                    return response()->json(['status' => 'Not Correct Status!']);
                }
            }
            else
            {
                return response()->json(['status' => 'Not Found'], 401);
            }
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }
}
