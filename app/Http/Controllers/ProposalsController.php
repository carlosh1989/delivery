<?php

namespace App\Http\Controllers;

/* Models */
use App\Costume;
use App\FeaturesLabels;
use App\FeaturesProposal;
use App\FeaturesValues;
use App\ProposalLines;
use App\ProposalValidation;
use App\RecipientsBussinessProposal;
use App\User;
use App\UsersBussinessProposal;
use App\UsersCalendar;
use App\UsersFeaturesDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use \Swift_Mailer;
use \Swift_Message;
use \Swift_SmtpTransport;

class ProposalsController extends Controller
{
    function getUserProposals(Request $request)
    {
        Carbon::setLocale('es');

            $user =  User::where('api_token', $request->header('Authorization'))->first();

            if($user)
            {
                //$result = UsersBussinessProposal::where('sender',$id)->get();
/*                $result = UsersBussinessProposal::where('sender',$id)->with(
                    'ProposalLines')->with('FeaturesProposal.FeaturesValues.FeaturesLabels')->with('Costume')->orderBy('id','DESC')->get();*/


                $propuestas = UsersBussinessProposal::where('sender',$user->id)->orderBy('id','DESC')->get();
                $propuesta = UsersBussinessProposal::where('sender',$user->id)->orderBy('id','DESC')->first();

                if($propuesta)
                {
                    foreach ($propuestas as $key => $value) 
                    {
                        $lines_all = ProposalLines::where('users_bussiness_proposal_id',$value->id)->get();                    foreach ($propuestas as $key => $value) 
                    {
                        $lines_all = ProposalLines::where('users_bussiness_proposal_id',$value->id)->get();
                        $costume_all = Costume::where('users_bussiness_proposal_id',$value->id)->get();
                        $features_proposal = FeaturesProposal::where('users_bussiness_proposal_id',$value->id)->get();

                        $talents = RecipientsBussinessProposal::where('users_bussiness_proposal_id',$value->id)
                        ->get(array('users_bussiness_proposal_id as id_row','recipient as id'));

                        $talentsProposal = array();
                        foreach ($talents as $key => $ww) 
                        {
                            $user = User::find($ww->id);

                            if($user->profilePhoto !="")
                            {
                                $photo = $user->profilePhoto;
                            }
                            else
                            {
                                $photo = "uploads/profilePhoto.png";
                            }

                            $talentsProposal[] = array(
                                'id' => $value->id,
                                'profilePhoto' => $photo
                            );
                        }

                        $line =  array();
                        foreach ($lines_all as $key => $ll) 
                        {
                            $line[] = array('filename' => $ll->filename);
                        }

                        $costume =  array();
                        foreach ($costume_all as $key => $cc) 
                        {
                            $costume[] = array('filename' => $cc->filename);
                        }

                        $features = array();
                        foreach ($features_proposal as $key => $ff) {
                            $features[] = array(
                                'label' => $ff->FeaturesValues->FeaturesLabels->featureLabel,
                                'value' => $ff->FeaturesValues->featureValue
                            );
                        }

                        $data[] =  [
                            'id'      => (int) $value->id,
                            'sender' => $value->sender,
                            'title'   => $value->title,
                            'description' => $value->description,
                            'initialPrice' => $value->initialPrice,
                            'castingType' => $value->castingType,
                            'rolType' => $value->rolType,
                            'filmingDate' => $value->filmingDate,
                            'hoursFilming' => $value->hoursFilming,
                            'productionType' => $value->productionType,
                            'filmingCity' => $value->filmingCity,
                            'filmingAddress' => $value->filmingAddress,
                            'filmingCoords' => $value->filmingCoords,
                            'created_at' => $value->created_at->diffForHumans(),
                            'lines' =>  $line,
                            'costume' =>  $costume,
                            'features' =>  $features,
                            'talents' =>  $talentsProposal
                        ];
                    }

                    if($data)
                    {
                        return response()->json( ['status'=>'ok', 'data'=>$data] , 200);   
                    }
                    else
                    {
                        return response()->json(['status'=>'Not found'] , 404 , []);
                    }
                        $costume_all = Costume::where('users_bussiness_proposal_id',$value->id)->get();
                        $features_proposal = FeaturesProposal::where('users_bussiness_proposal_id',$value->id)->get();

                        $line =  array();
                        foreach ($lines_all as $key => $ll) 
                        {
                            $line[] = array('filename' => $ll->filename);
                        }

                        $costume =  array();
                        foreach ($costume_all as $key => $cc) 
                        {
                            $costume[] = array('filename' => $cc->filename);
                        }

                        $features = array();
                        foreach ($features_proposal as $key => $ff) {
                            $features[] = array(
                                'label' => $ff->FeaturesValues->FeaturesLabels->featureLabel,
                                'value' => $ff->FeaturesValues->featureValue
                            );
                        }

                        $data[] = (object) [
                            'id'      => (int) $value->id,
                            'sender' => $value->sender,
                            'title'   => $value->title,
                            'description' => $value->description,
                            'initialPrice' => $value->initialPrice,
                            'castingType' => $value->castingType,
                            'rolType' => $value->rolType,
                            'filmingDate' => $value->filmingDate,
                            'hoursFilming' => $value->hoursFilming,
                            'productionType' => $value->productionType,
                            'filmingCity' => $value->filmingCity,
                            'filmingAddress' => $value->filmingAddress,
                            'filmingCoords' => $value->filmingCoords,
                            'created_at' => $value->created_at->diffForHumans(),
                            'lines' => (object) $line,
                            'costume' => (object) $costume,
                            'features' => (object) $features,

                        ];
                    }

                    return response()->json( ['status'=>'ok', 'data'=>$data] , 200); 
                }
                else
                {
                    return response()->json( ['status'=>'ok', 'data'=>$propuestas] , 200); 
                }
            }
            else
            {
                return response()->json(['error'=>'Unauthorized'] , 401 , []);
            }
    }


    function getTalentProposals(Request $request)
    {
        Carbon::setLocale('es');

        $user =  User::where('api_token', $request->header('Authorization'))->first();

        if($user)
        {
            $recipient = RecipientsBussinessProposal::where('recipient',$user->id)->first();

            if($recipient)
            {
                $recipients = RecipientsBussinessProposal::where('recipient',$user->id)->get();
                $data = array();

                foreach ($recipients as $key => $re) 
                {
                    if($re->proposal_estatus !== 5)
                    {
                        $proposal = UsersBussinessProposal::find($re->users_bussiness_proposal_id);

                        $data[] = array(
                            'id' => $proposal->id,
                            'title' => $proposal->title,
                            'description' => $proposal->description,
                            'renegotiatedPrice' => $re->renegotiatedPrice,
                            'estatus' => $re->proposal_estatus,
                        );
                    }
                }

                return response()->json( ['status'=>'ok', 'data'=>$data] , 200); 
            }
            else
            {
                return response()->json(['status'=>'Not Found'] , 401 , []);
            }
        }
        else
        {
            return response()->json(['error'=>'Unauthorized'] , 401 , []);
        }
    }



//             $user =  User::where('api_token', $request->header('Authorization'))->first();

//             if($user)
//             {
//                 //$result = UsersBussinessProposal::where('sender',$id)->get();
// /*                $result = UsersBussinessProposal::where('sender',$id)->with(
//                     'ProposalLines')->with('FeaturesProposal.FeaturesValues.FeaturesLabels')->with('Costume')->orderBy('id','DESC')->get();*/


//                 $propuestas = UsersBussinessProposal::where('sender',$user->id)->orderBy('id','DESC')->get();
//                 $propuesta = UsersBussinessProposal::where('sender',$user->id)->orderBy('id','DESC')->first();

//                 if($propuesta)
//                 {
//                     foreach ($propuestas as $key => $value) 
//                     {
//                         $lines_all = ProposalLines::where('users_bussiness_proposal_id',$value->id)->get();                    foreach ($propuestas as $key => $value) 
//                     {
//                         $lines_all = ProposalLines::where('users_bussiness_proposal_id',$value->id)->get();
//                         $costume_all = Costume::where('users_bussiness_proposal_id',$value->id)->get();
//                         $features_proposal = FeaturesProposal::where('users_bussiness_proposal_id',$value->id)->get();

//                         $talents = RecipientsBussinessProposal::where('users_bussiness_proposal_id',$value->id)
//                         ->get(array('users_bussiness_proposal_id as id_row','recipient as id'));

//                         $talentsProposal = array();
//                         foreach ($talents as $key => $ww) 
//                         {
//                             $user = User::find($ww->id);

//                             if($user->profilePhoto !="")
//                             {
//                                 $photo = $user->profilePhoto;
//                             }
//                             else
//                             {
//                                 $photo = "uploads/profilePhoto.png";
//                             }

//                             $talentsProposal[] = array(
//                                 'id' => $value->id,
//                                 'profilePhoto' => $photo
//                             );
//                         }

//                         $line =  array();
//                         foreach ($lines_all as $key => $ll) 
//                         {
//                             $line[] = array('filename' => $ll->filename);
//                         }

//                         $costume =  array();
//                         foreach ($costume_all as $key => $cc) 
//                         {
//                             $costume[] = array('filename' => $cc->filename);
//                         }

//                         $features = array();
//                         foreach ($features_proposal as $key => $ff) {
//                             $features[] = array(
//                                 'label' => $ff->FeaturesValues->FeaturesLabels->featureLabel,
//                                 'value' => $ff->FeaturesValues->featureValue
//                             );
//                         }

//                         $data[] =  [
//                             'id'      => (int) $value->id,
//                             'sender' => $value->sender,
//                             'title'   => $value->title,
//                             'description' => $value->description,
//                             'initialPrice' => $value->initialPrice,
//                             'castingType' => $value->castingType,
//                             'rolType' => $value->rolType,
//                             'filmingDate' => $value->filmingDate,
//                             'hoursFilming' => $value->hoursFilming,
//                             'productionType' => $value->productionType,
//                             'filmingCity' => $value->filmingCity,
//                             'filmingAddress' => $value->filmingAddress,
//                             'filmingCoords' => $value->filmingCoords,
//                             'created_at' => $value->created_at->diffForHumans(),
//                             'lines' =>  $line,
//                             'costume' =>  $costume,
//                             'features' =>  $features,
//                             'talents' =>  $talentsProposal
//                         ];
//                     }

//                     if($data)
//                     {
//                         return response()->json( ['status'=>'ok', 'data'=>$data] , 200);   
//                     }
//                     else
//                     {
//                         return response()->json(['status'=>'Not found'] , 404 , []);
//                     }
//                         $costume_all = Costume::where('users_bussiness_proposal_id',$value->id)->get();
//                         $features_proposal = FeaturesProposal::where('users_bussiness_proposal_id',$value->id)->get();

//                         $line =  array();
//                         foreach ($lines_all as $key => $ll) 
//                         {
//                             $line[] = array('filename' => $ll->filename);
//                         }

//                         $costume =  array();
//                         foreach ($costume_all as $key => $cc) 
//                         {
//                             $costume[] = array('filename' => $cc->filename);
//                         }

//                         $features = array();
//                         foreach ($features_proposal as $key => $ff) {
//                             $features[] = array(
//                                 'label' => $ff->FeaturesValues->FeaturesLabels->featureLabel,
//                                 'value' => $ff->FeaturesValues->featureValue
//                             );
//                         }

//                         $data[] = (object) [
//                             'id'      => (int) $value->id,
//                             'sender' => $value->sender,
//                             'title'   => $value->title,
//                             'description' => $value->description,
//                             'initialPrice' => $value->initialPrice,
//                             'castingType' => $value->castingType,
//                             'rolType' => $value->rolType,
//                             'filmingDate' => $value->filmingDate,
//                             'hoursFilming' => $value->hoursFilming,
//                             'productionType' => $value->productionType,
//                             'filmingCity' => $value->filmingCity,
//                             'filmingAddress' => $value->filmingAddress,
//                             'filmingCoords' => $value->filmingCoords,
//                             'created_at' => $value->created_at->diffForHumans(),
//                             'lines' => (object) $line,
//                             'costume' => (object) $costume,
//                             'features' => (object) $features,

//                         ];
//                     }

//                     return response()->json( ['status'=>'ok', 'data'=>$data] , 200); 
//                 }
//                 else
//                 {
//                     return response()->json( ['status'=>'ok', 'data'=>$propuestas] , 200); 
//                 }
//             }
//             else
//             {
//                 return response()->json(['error'=>'Unauthorized'] , 401 , []);
//             }
    


    function getProposal(Request $request, $id)
    {
        if($id)
        {
            $user =  User::where('api_token', $request->header('Authorization'))->first();

            if($user)
            {
                //$result = UsersBussinessProposal::where('sender',$id)->get();
/*                $result = UsersBussinessProposal::where('sender',$id)->with(
                    'ProposalLines')->with('FeaturesProposal.FeaturesValues.FeaturesLabels')->with('Costume')->orderBy('id','DESC')->get();*/


                $propuestas = UsersBussinessProposal::where('id',$id)->orderBy('id','DESC')->get();

                foreach ($propuestas as $key => $value) 
                {
                    $agency = User::find($value->agency_id);

                    $lines_all = ProposalLines::where('users_bussiness_proposal_id',$value->id)->get();
                    $costume_all = Costume::where('users_bussiness_proposal_id',$value->id)->get();
                    $features_proposal = FeaturesProposal::where('users_bussiness_proposal_id',$value->id)->get();



                    $talents = RecipientsBussinessProposal::where('users_bussiness_proposal_id',$value->id)
                    ->get(array('users_bussiness_proposal_id as id_row','recipient as id'));

                    $agency = User::find($value->agency_id);

                    if($agency)
                    {
                        $agencyName = $agency->name;
                    }
                    else
                    {
                        $agencyName = "";
                    }

                    $talentsProposal = array();
                    foreach ($talents as $key => $ww) 
                    {
                        $user = User::find($ww->id);

                        if($user->profilePhoto !="")
                        {
                            $photo = $user->profilePhoto;
                        }
                        else
                        {
                            $photo = "uploads/profilePhoto.png";
                        }

                        $talentsProposal[] = array(
                            'id' => $value->id,
                            'profilePhoto' => $photo
                        );
                    }

                    $line =  array();
                    foreach ($lines_all as $key => $ll) 
                    {
                        //$line[] = array('filename' => $ll->filename);
                        $line[] = array(
                            'id' => $ll->id,
                            'src' => url($ll->filename),
                            'thumbnail' => url($ll->filename),
                            'thumbnailWidth' => 200,
                            'thumbnailHeight' => 200
                        );
                    }

                    $costume =  array();
                    foreach ($costume_all as $key => $cc) 
                    {
                        //$costume[] = array('filename' => $cc->filename);
                        $costume[] = array(
                            'id' => $cc->id,
                            'src' => url($cc->filename),
                            'thumbnail' => url($cc->filename),
                            'thumbnailWidth' => 200,
                            'thumbnailHeight' => 200
                        );
                    }

                    $features = array();
                    $skilles = array();
                    foreach ($features_proposal as $key => $ff) {

                        if($ff->FeaturesValues->FeaturesLabels->FeaturesCategories->id == 3)
                        {
                            $features[] = array(
                                'label' => $ff->FeaturesValues->FeaturesLabels->featureLabel,
                                'value' => $ff->FeaturesValues->featureValue,
                                'categorie' => $ff->FeaturesValues->FeaturesLabels->FeaturesCategories->id
                            );
                        }
                        

                        if($ff->FeaturesValues->FeaturesLabels->FeaturesCategories->id == 2)
                        {
                            $skilles[] = array(
                                'label' => $ff->FeaturesValues->FeaturesLabels->featureLabel,
                                'value' => $ff->FeaturesValues->featureValue,
                                'categorie' => $ff->FeaturesValues->FeaturesLabels->FeaturesCategories->id
                            );
                        }

                    }

                    $data = [
                        'id'      => (int) $value->id,
                        'sender' => $value->sender,
                        'title'   => $value->title,
                        'description' => $value->description,
                        'initialPrice' => $value->initialPrice,
                        'castingType' => $value->castingType,
                        'rolType' => $value->rolType,
                        'filmingDate' => $value->filmingDate,
                        'hoursFilming' => $value->hoursFilming,
                        'productionType' => $value->productionType,
                        'filmingCity' => $value->filmingCity,
                        'filmingAddress' => $value->filmingAddress,
                        'filmingCoords' => $value->filmingCoords,
                        'lines' =>  $line,
                        'costume' => $costume,
                        'features' =>  $features,
                        'skilles'   => $skilles,
                        'talents' => $talentsProposal,
                        'asistensePrice' => $value->asistensePrice,
                        'assistanceDescription' => $value->assistanceDescription,
                        'agencyName' => $agencyName,
                    ];
                }

                if($propuestas)
                {
                    $data2 = (object )$data;
                    //echo is_array($data2) ? 'Array' : 'No es un array';
                    return response()->json( ['status'=>'ok', 'data'=>$data] , 200);   
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

    function getProposal2(Request $request, $id)
    {
        if($id)
        {
            $user =  User::where('api_token', $request->header('Authorization'))->first();

            if($user)
            {
                //$result = UsersBussinessProposal::find($id);
/*                $result = UsersBussinessProposal::with(
                    'ProposalLines',
                    'FeaturesProposal.FeaturesValues.FeaturesLabels',
                    'Costume'
                )->orderBy('id','DESC')->find($id);
*/
                $propuestas = UsersBussinessProposal::all();

                //$propuestas = UsersBussinessProposal::where('sender',$id)->orderBy('id','DESC')->get();

                foreach ($propuestas as $key => $value) 
                {
                    $lines_all = ProposalLines::where('users_bussiness_proposal_id',$value->id)->get();
                    $costume_all = Costume::where('users_bussiness_proposal_id',$value->id)->get();
                    $features_proposal = FeaturesProposal::where('users_bussiness_proposal_id',$value->id)->get();

                    $line =  array();
                    foreach ($lines_all as $key => $ll) 
                    {
                        $line[] = array('filename' => $ll->filename);
                    }

                    $costume =  array();
                    foreach ($costume_all as $key => $cc) 
                    {
                        $costume[] = array('filename' => $cc->filename);
                    }

                    $features = array();
                    foreach ($features_proposal as $key => $ff) {
                        $features[] = array(
                            'label' => $ff->FeaturesValues->FeaturesLabels->featureLabel,
                            'value' => $ff->FeaturesValues->featureValue
                        );
                    }

                    $data[] =  [
                        'id'      => (int) $value->id,
                        'sender' => $value->sender,
                        'title'   => $value->title,
                        'description' => $value->description,
                        'initialPrice' => $value->initialPrice,
                        'castingType' => $value->castingType,
                        'rolType' => $value->rolType,
                        'filmingDate' => $value->filmingDate,
                        'hoursFilming' => $value->hoursFilming,
                        'productionType' => $value->productionType,
                        'filmingCity' => $value->filmingCity,
                        'filmingAddress' => $value->filmingAddress,
                        'filmingCoords' => $value->filmingCoords,
                        'lines' =>  $line,
                        'costume' =>  $costume,
                        'features' =>  $features,
                    ];
                }

                

                if($propuestas)
                {
                    $fractal = new Manager();
                    $encodedSku = (object) $data;
                    //echo is_array($encodedSku) ? 'Array' : 'No es un array';
                    //return $fractal->createData($data)->toJson();
                    return response()->json( ['status'=>'ok', 'data'=>$encodedSku] , 200);   
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

    function getAllsProposals(Request $request)
    {
        $user =  User::where('api_token', $request->header('Authorization'))->first();

        if($user)
        {
            //$results = UsersBussinessProposal::orderBy('id')->get();
/*            $results= UsersBussinessProposal::with(
                'ProposalLines',
                'FeaturesProposal.FeaturesValues.FeaturesLabels',
                'Costume'
            )->orderBy('id','DESC')->get();*/

            //$results = UsersBussinessProposal::all()->toArray();
            $propuestas = UsersBussinessProposal::orderBy('id','DESC')->get();

            foreach ($propuestas as $key => $value) 
            {
                $lines_all = ProposalLines::where('users_bussiness_proposal_id',$value->id)->get();
                $costume_all = Costume::where('users_bussiness_proposal_id',$value->id)->get();
                $features_proposal = FeaturesProposal::where('users_bussiness_proposal_id',$value->id)->get();

                $line =  array();
                foreach ($lines_all as $key => $ll) 
                {
                    $line[] = array('filename' => $ll->filename);
                }

                $costume =  array();
                foreach ($costume_all as $key => $cc) 
                {
                    $costume[] = array('filename' => $cc->filename);
                }

                $features = array();
                foreach ($features_proposal as $key => $ff) {
                    $features[] = array(
                        'label' => $ff->FeaturesValues->FeaturesLabels->featureLabel,
                        'value' => $ff->FeaturesValues->featureValue
                    );
                }

                $data[] = (object) [
                    'id'      => (int) $value->id,
                    'sender' => $value->sender,
                    'title'   => $value->title,
                    'description' => $value->description,
                    'initialPrice' => $value->initialPrice,
                    'castingType' => $value->castingType,
                    'rolType' => $value->rolType,
                    'filmingDate' => $value->filmingDate,
                    'hoursFilming' => $value->hoursFilming,
                    'productionType' => $value->productionType,
                    'filmingCity' => $value->filmingCity,
                    'filmingAddress' => $value->filmingAddress,
                    'filmingCoords' => $value->filmingCoords,
                    'lines' => (object) $line,
                    'costume' => (object) $costume,
                    'features' => (object) $features,
                ];
            }

            if($propuestas)
            {
                //echo $fractal->createData($resource)->toJson();  
                //return $object;
                return response()->json( ['status'=>'ok', 'data'=>$data] , 200);    
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

    function createProposal(Request $request)
    {
        $user =  User::where('api_token', $request->header('Authorization'))->first();

        list($mes,$dia,$ano) = explode('/', Input::get('filmingDate'));

        if($mes < 10)
        {
            $mes;
        }

        $filmingDate = $ano.'-'.$mes.'-'.$dia;
        //return $filmingDate;
        $fecha_actual = strtotime(date("Y-m-d"));
        $fecha_entrada = strtotime($filmingDate);

        if($fecha_entrada >= $fecha_actual)
        {
            //return 'ok date';
        }
        else
        {
            return response()->json(['status'=>'Date Error'],411);
        }

        if($user)
        {
            $this->validate($request, [
                'proposalType' => 'required',
                'title' => 'required',
                'description' => 'required',
                'initialPrice' => 'required',
                'castingType' => 'required',
                'rolType' => 'required',
                'filmingDate' => 'required',
                'hoursFilming' => 'required',
                'productionType' => 'required',
                'filmingCity' => 'required',
                'filmingAddress' => 'required',
                'features' => 'required'
            ]); 

            $proposal = UsersBussinessProposal::create([
                    'sender'        => $user['id'], 
                    'agency_id'     => Input::get('agency_id'),
                    'proposalType'  => Input::get('proposalType'),
                    'title'         => Input::get('title'),
                    'description'   => Input::get('description'),
                    'initialPrice'  => Input::get('initialPrice'),
                    'castingType'   => Input::get('castingType'),
                    'rolType'       => Input::get('rolType'),
                    'filmingDate'   => $filmingDate,
                    'hoursFilming'  => Input::get('hoursFilming'),
                    'productionType'=> Input::get('productionType'),
                    'filmingCity'   => Input::get('filmingCity'),
                    'filmingAddress'=> Input::get('filmingAddress'),
                    'filmingCoords' => Input::get('filmingCoords'),
                    'rolName'       => Input::get('rolName'),
                    'assistanceDescription' => Input::get('assistanceDescription'),
                    'asistensePrice'  => Input::get('asistensePrice'),
            ]);

            $featuresPro = Input::get('features');

            if(count($featuresPro) > 0)
            {
                // Guardar las caracteristicas talentos
                foreach ($featuresPro as $value) {
                    
                    if($value)
                    {
                        FeaturesProposal::create([
                            'users_bussiness_proposal_id' => $proposal['id'],
                            'features_values_id' => $value
                        ]);
                    }
                    else
                    {
                        return response()->json(['error'=>'Length Required'] , 411 , []);
                    }
                }
            }
            else
            {
                return response()->json(['error'=>'Length Required'] , 411 , []);
            }

            // Si se envian referencias de vestuario o lineas; Generar codigo para la carpeta donde se guardaran los archivos
            if ($request->hasFile('costume') || $request->hasFile('lines'))
            {
                $idPath = bin2hex(openssl_random_pseudo_bytes(16));
            }

            // Guardar los archivos de vestuario
            if ($request->hasFile('costume'))
            {   
                // Carpeta donde se guardaran las fotos
                $path = 'uploads/proposals-posts/'.$idPath.'/costume';
                
                // Recorrer el array de fotos
                foreach ($request->file('costume') as $photo)
                {   
                    // Quitar espacios en blanco de los nombres de las fotos
                    $fileName = str_ireplace(' ', '-', $photo->getClientOriginalName());
                   
                    // Guardar las fotos en la carpeta
                    $photo->move($path, $fileName);

                    
                    // Guardar los nombres de las fotos en la tabla
                     Costume::create([
                        'users_bussiness_proposal_id'   => $proposal['id'], 
                        'filename'                      =>  $path.'/'.$fileName
                    ]);

                }
            }


            // Guardar los archivos de lineas
            if ($request->hasFile('lines'))
            {   
                // Carpeta donde se guardaran las fotos
                $path = 'uploads/proposals-posts/'.$idPath.'/lines';
                
                // Recorrer el array de fotos
                foreach ($request->file('lines') as $photo)
                {   
                    // Quitar espacios en blanco de los nombres de las fotos
                    $fileName = str_ireplace(' ', '-', $photo->getClientOriginalName());
                   
                    // Guardar las fotos en la carpeta
                    $photo->move($path, $fileName);

                    
                    // Guardar los nombres de las fotos en la tabla
                     ProposalLines::create([
                        'users_bussiness_proposal_id'   => $proposal['id'], 
                        'filename'                      =>  $path.'/'.$fileName
                    ]);

                }
            } 

            if($proposal->id)
            {
                return response()->json( ['status'=>'proposal created','id'=>$proposal->id] , 201 , []);   
            }
            else
            {
                return response()->json(['status'=>'Bad Request, Error Create Proposal'] , 400 , []);
            }
        }   
        else
        {
            return response()->json(['error'=>'Unauthorized'] , 401 , []);
        }
    }

    function createProposal2(Request $request){


        $fecha_actual = strtotime(date("Y-m-d"));
        $fecha_entrada = strtotime("2019-05-17");

        if($fecha_entrada >= $fecha_actual)
        {
        echo "ok";
        }
        else
        {
        echo "error";
        }

        // --
        // Continue . . .
            
            // Guardar la propuesta
            $proposal = UsersBussinessProposal::create([
                    'sender'        => $user['id'], 
                    'proposalType'  => Input::get('proposalType'),
                    'title'         => Input::get('title'),
                    'description'   => Input::get('description'),
                    'initialPrice'  => Input::get('initialPrice'),
                    'castingType'   => Input::get('castingType'),
                    'rolType'       => Input::get('rolType'),
                    'filmingDate'   => Input::get('filmingDate'),
                    'hoursFilming'  => Input::get('hoursFilming'),
                    'productionType'=> Input::get('productionType'),
                    'filmingCity'   => Input::get('filmingCity'),
                    'filmingAddress'=> Input::get('filmingAddress'),
                    'filmingCoords' => Input::get('filmingCoords'),
            ]);

            $featuresPro = Input::get('features');

            // Guardar las caracteristicas talentos
            foreach ($featuresPro as $value) {
                
                FeaturesProposal::create([
                    'users_bussiness_proposal_id' => $proposal['id'],
                    'features_values_id' => $value
                ]);
            }

            // Si se envian referencias de vestuario o lineas; Generar codigo para la carpeta donde se guardaran los archivos
            if ($request->hasFile('costume') || $request->hasFile('lines'))
            {
                $idPath = bin2hex(openssl_random_pseudo_bytes(16));
            }

            // Guardar los archivos de vestuario
            if ($request->hasFile('costume'))
            {   
                // Carpeta donde se guardaran las fotos
                $path = 'uploads/proposals-posts/'.$idPath.'/costume';
                
                // Recorrer el array de fotos
                foreach ($request->file('costume') as $photo)
                {   
                    // Quitar espacios en blanco de los nombres de las fotos
                    $fileName = str_ireplace(' ', '-', $photo->getClientOriginalName());
                   
                    // Guardar las fotos en la carpeta
                    $photo->move($path, $fileName);

                    
                    // Guardar los nombres de las fotos en la tabla
                     Costume::create([
                        'users_bussiness_proposal_id'   => $proposal['id'], 
                        'filename'                      =>  $path.'/'.$fileName
                    ]);

                }
            }


            // Guardar los archivos de lineas
            if ($request->hasFile('lines'))
            {   
                // Carpeta donde se guardaran las fotos
                $path = 'uploads/proposals-posts/'.$idPath.'/lines';
                
                // Recorrer el array de fotos
                foreach ($request->file('lines') as $photo)
                {   
                    // Quitar espacios en blanco de los nombres de las fotos
                    $fileName = str_ireplace(' ', '-', $photo->getClientOriginalName());
                   
                    // Guardar las fotos en la carpeta
                    $photo->move($path, $fileName);

                    
                    // Guardar los nombres de las fotos en la tabla
                     ProposalLines::create([
                        'users_bussiness_proposal_id'   => $proposal['id'], 
                        'filename'                      =>  $path.'/'.$fileName
                    ]);

                }
            } 
            
            return response()->json( $proposal , 201 );
    }

    function addTalentProposal(Request $request)
    {
        if($request->isJson())
        {
          //API token auth
          $user =  User::where('api_token', $request->header('Authorization'))->first();

          if($user)
          {
            $data = $request->json()->all();
            $recipient = $data['recipient']; 
            $proposal_id = $data['users_bussiness_proposal_id'];

            $proposal = UsersBussinessProposal::where('id',$proposal_id)
            ->where('sender',$user['id'])
            ->first();

            if($proposal)
            {
                if($recipient and $proposal_id)
                {
                    $recipient_exists = RecipientsBussinessProposal::where('recipient',$recipient)->where('users_bussiness_proposal_id', $proposal_id)->first();

                    if(!$recipient_exists)
                    {
                        $dayCalndar = UsersCalendar::where('users_id',$recipient)->where('date',$proposal->filmingDate)->first();

                        if(!$dayCalndar)
                        {
                            $recipient_add = RecipientsBussinessProposal::create([
                                    'recipient' => $recipient, 
                                    'users_bussiness_proposal_id' => $proposal_id,
                            ]);

                            $validation = ProposalValidation::where('users_bussiness_proposal_id',$proposal_id)
                            ->where('recipient',$recipient)
                            ->first();

                            if(!$validation)
                            {
                                ProposalValidation::create([
                                    'users_bussiness_proposal_id'=>$proposal_id,
                                    'added' => 1,
                                    'recipient' => $recipient,
                                    'updated_at' => Carbon::now()
                                ]);

                                $userRecipient = User::find($recipient);



                                //enviar correo
                                sendEmail(
                                    [
                                        'authWith' => 'info@posteayvende.com',
                                        'setSubject' => 'Recover account',
                                        'setFrom' => ['info@rdcasting.com', 'Info'],
                                        'emailbody' => 'ACEPTAR PROPUESTA',
                                        'to' => $userRecipient->email,
                                    ]
                                );
                            }
                            else
                            {
                                $validation->added = 2;
                                $validation->updated_at = Carbon::now();
                                $validation->save();

                                //enviar correo en una hora
                            }

                            return response()->json( ['status'=>'talent add','id'=>$recipient_add->id] , 201 , []);
                        }
                        else
                        {
                            return response()->json(['status'=>'busy talent day'],401);
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
          else
          {
            return response()->json(['error'=>'Unauthorized'] , 401 , []);
          }
        }
        else
        {
            return response()->json(['error'=>'Bad Request'] , 400 , []);
        }
    }

    function getTalentsProposal(Request $request, $id)
    {
        $user =  User::where('api_token', $request->header('Authorization'))->first();
        $data = array();
        if($user)
        {
            if($id)
            {
                $talents = RecipientsBussinessProposal::where('users_bussiness_proposal_id',$id)
                ->get(array('users_bussiness_proposal_id as id_row','recipient as id','proposal_estatus','renegotiatedPrice'));


                foreach ($talents as $key => $value) 
                {
                    $user = User::find($value->id);

                    if($user->profilePhoto !="")
                    {
                        $photo = $user->profilePhoto;
                    }
                    else
                    {
                        $photo = "uploads/profilePhoto.png";
                    }

                    $data[] = (object) array(
                        'id' => $value->id,
                        'id_row' => $value->id_row,
                        'profilePhoto' => $photo,
                        'estatus' => $value->proposal_estatus,
                        'renegotiatedPrice' => $value->renegotiatedPrice
                    );
                }

                return $data;   
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

    function deleteTalentProposal(Request $request)
    {
        if($request->isJson())
        {
          //API token auth
          $user =  User::where('api_token', $request->header('Authorization'))->first();

          if($user)
          {
            $data = $request->json()->all();
            $recipient = $data['recipient']; 
            $proposal_id = $data['users_bussiness_proposal_id'];

            $proposal = RecipientsBussinessProposal::where('recipient',$recipient)
            ->where('users_bussiness_proposal_id', $proposal_id)
            ->delete();
            
            return response()->json( ['status'=>'talent deleted of proposal'], 200, []);
          }
          else
          {
            return response()->json(['error'=>'Unauthorized'] , 401 , []);
          }
        }
        else
        {
            return response()->json(['error'=>'Bad Request'] , 400 , []);
        }
    }

    function sendEmailRecipientsAgain()
    {
        $today = Carbon::now()->format('Y-m-d').'%';
        //$proposalValidation = ProposalValidation::where('updated_at', 'like', $today)->get();
        $proposalValidation = ProposalValidation::whereDate('updated_at', '<=', date('Y-m-d'))->get();

        foreach ($proposalValidation as $key => $value) 
        {
            $to = Carbon::createFromFormat('Y-m-d H:s:i', $value->updated_at);
            $from = Carbon::createFromFormat('Y-m-d H:s:i', Carbon::now());
            $diff_in_hours = $to->diffInHours($from);
            //print_r($diff_in_hours);// Output: 6

            if($diff_in_hours == 1)
            {
                //enviar correo
                sendEmail(
                    [
                        'authWith' => 'info@posteayvende.com',
                        'setSubject' => 'Recover account',
                        'setFrom' => ['info@rdcasting.com', 'Info'],
                        'emailbody' => 'ACEPTAR PROPUESTA',
                        'to' => $value->user->email,
                    ]
                );
            }   
        }

        return 'ok';
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

    function userProposalAccepted(Request $request, $id)
    {
        //Verificar que es el propietario de la cuenta
        $data = $request->json()->all();
        $user =  User::where('api_token', $request->header('Authorization'))->first();
        
        if ($user) 
        {
            if(!empty($data['status_accepted']))
            {
                $proposal = RecipientsBussinessProposal::where('recipient',$user->id)->where('users_bussiness_proposal_id',$id)->first();  

                if($proposal)
                {
                    $proposal->accepted = $data['status_accepted'];
                    $proposal->save();

                    return response()->json(['status' => 'Proposal recipient accepted updated'] , 200 );
                }
                else
                {
                    return response()->json(['error'=>'Not Found'] , 400 , []);  
                }
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
}

