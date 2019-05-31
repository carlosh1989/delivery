<?php

namespace App\Http\Controllers;

/* Models */
use App\BankAccounts;
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

class BankAccountsController extends Controller
{

    function addBankAccount(Request $request)
    {
        $data = $request->json()->all();

        $user =  User::where('api_token', $request->header('Authorization'))->first();

        if($user)
        {
            if($data['owner'] AND $data['certificate_passport'] AND $data['bank'] AND $data['account_number'])
            {
                BankAccounts::create([
                    'users_id' => $user->id,
                    'owner' => $data['owner'],
                    'certificate_passport' => $data['certificate_passport'],
                    'bank' => $data['bank'],
                    'account_number' => $data['account_number']
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

    function getBankAccount(Request $request)
    {
        $user =  User::where('api_token', $request->header('Authorization'))->first();  

        if($user)
        {
            $bank_accouts = BankAccounts::where('users_id',$user->id)->get();
            return response()->json($bank_accouts, 200);
        }
        else
        {
            return response()->json(['error' => 'Unauthorized'] , 401 , []);
        }
    }

    function getBankAccountDetail(Request $request, $id)
    {
        $user =  User::where('api_token', $request->header('Authorization'))->first();  

        if($user)
        {
            $bank_accouts = BankAccounts::where('users_id',$user->id)->where('id',$id)->get();
            return response()->json($bank_accouts, 200);
        }
        else
        {
            return response()->json(['error' => 'Unauthorized'] , 401 , []);
        }
    }

    function BankAccountUpdate(Request $request, $id)
    {
        $data = $request->json()->all();

        $user =  User::where('api_token', $request->header('Authorization'))->first();
        
        if ($user) 
        {
            $bank_account_update = BankAccounts::where('users_id', $user->id)->where('id',$id)->first();

            if($bank_account_update)
            {
                $bank_account_update->update($data);
                return response()->json(['status' => 'Bank Account Updated!'], 200 );
            }
            else
            {
                return response()->json(['status' => 'Bank Account Not Found!'], 404 );
            }
            //$bank_account_update = BankAccounts::where('users_id',$user->id)
            //->where('id',$id)->update($data);
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }

    function BankAccountDelete(Request $request, $id)
    {
        $data = $request->json()->all();

        $user =  User::where('api_token', $request->header('Authorization'))->first();
        
        if ($user) 
        {
            $user_update = User::find($user->id)->update($data);

            $bank = BankAccounts::where('users_id',$user->id)->where('id',$id)->first();

            if($bank)
            {
                $bank->delete();
                return response()->json(['status'=>'Bank Account deleted!'], 200);
            }
            else
            {
                return response()->json(['status'=>'Bank Account not found!'], 404);
            }
        }
        else
        {
            return response()->json(['error','Unauthorized'] , 401 , []);
        }
    }
}
