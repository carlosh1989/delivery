<?php
namespace App\Http\Controllers;

/* Models */
use App\FeaturesLabels;
use App\FeaturesProposal;
use App\FeaturesValues;
use App\RecipientsBussinessProposal;
use App\User;
use App\UsersBussinessProposal;
use App\UsersCalendar;
use App\UsersFeaturesDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Swift_Mailer;
use \Swift_Message;
use \Swift_SmtpTransport;

class SearchController extends Controller
{

  function searchTalentsFeaturesCount(Request $request, $id)
  {
    if($request->isJson())
    {
      $user =  User::where('api_token', $request->header('Authorization'))->first();

      if($user)
      {
        $proposalUser = UsersBussinessProposal::where('id',$id)
        ->where('sender',$user->id)
        ->first();

        if($proposalUser)
        {
          $featuresProposal = FeaturesProposal::select('features_values_id')->where('users_bussiness_proposal_id',$id)->get();

          $line =  array();
          foreach ($featuresProposal as $key => $ll) 
          {
              $line[] = $ll->features_values_id;
          }

          $values = $line;

          $line_count = count($line);

          $ids = User::select('id')
          ->with(array('users_features_detail' => function($query) use ($values) {

          $query->select('users_features_detail.id','users_id','features_values_id','featureValue')
          ->join('features_values','features_values.id','=','users_features_detail.features_values_id')
          ->whereIn('users_features_detail.features_values_id', $values); 
          }))

          ->whereHas('users_features_detail', function($query) use ($values) {

          $query->select('users_features_detail.id','users_id','features_values_id','featureValue')
          ->join('features_values','features_values.id','=','users_features_detail.features_values_id')
          ->whereIn('users_features_detail.features_values_id', $values); 

          })->get();

          $userFea = array();
          $num = 0;
          foreach ($ids as $key => $value) 
          {
            $fea_num = count($value->users_features_detail);

            $recipients = RecipientsBussinessProposal::where('recipient',$value->id)
            ->where('users_bussiness_proposal_id',$id)
            ->first();

            if(!$recipients)
            {
              if($fea_num >= $line_count)
              {
                //$userFea[] = $value;
                $num = $num + 1;
              }
            }
          }

          return  $num;

        }
        else
        {
          return response()->json(['error'=>'Dont have permision'] , 401 , []);
        }
      }
      else
      {
        return response()->json(['error'=>'Unauthorized'] , 401 , []);
      }
    }
    else
    {
      return response()->json(['error','Bad Request'] , 400 , []);
    }
  }

  function searchTalentsFeatures(Request $request, $id)
  {

      $user =  User::where('api_token', $request->header('Authorization'))->first();

      if($user)
      {
        $proposalUser = UsersBussinessProposal::where('id',$id)
        ->where('sender',$user->id)
        ->first();

        if($proposalUser)
        {
          $filmingDate = $proposalUser->filmingDate;
          $featuresProposal = FeaturesProposal::select('features_values_id')->where('users_bussiness_proposal_id',$id)->get();

          $line =  array();
          foreach ($featuresProposal as $key => $ll) 
          {
              $line[] = $ll->features_values_id;
          }

          $values = $line;

          $line_count = count($line);

          $ids = User::select('id')
          ->with(array('users_features_detail' => function($query) use ($values) {

          $query->select('users_features_detail.id','users_id','features_values_id','featureValue')
          ->join('features_values','features_values.id','=','users_features_detail.features_values_id')
          ->whereIn('users_features_detail.features_values_id', $values); 
          }))

          ->whereHas('users_features_detail', function($query) use ($values) {

          $query->select('users_features_detail.id','users_id','features_values_id','featureValue')
          ->join('features_values','features_values.id','=','users_features_detail.features_values_id')
          ->whereIn('users_features_detail.features_values_id', $values); 

          })->get();

          $data = array();
          $userFea = array();
          foreach ($ids as $key => $value) 
          {
            $fea_num = count($value->users_features_detail);

            $recipients = RecipientsBussinessProposal::where('recipient',$value->id)
            ->where('users_bussiness_proposal_id',$id)
            ->first();

            if(!$recipients)
            {
              if($fea_num >= $line_count)
              {
                $userFea[] = $value;

                  $userData = User::find($value->id);

                  if($userData->profilePhoto !="")
                  {
                    $photo = $userData->profilePhoto;
                  }
                  else
                  {
                    $photo = "uploads/profilePhoto.png";
                  }

                  $users_features_detail =  array();
                  foreach ($value->users_features_detail as $key => $cc) 
                  {
                      $users_features_detail[] = array(
                        'id' => $cc->id,
                        'users_id' => $cc->users_id,
                        'features_values_id' => $cc->features_values_id,
                        'featureValue' => $cc->featureValue
                    );

                  }

                  $calendar = UsersCalendar::where('users_id', $value->id)
                  ->where('date', 'like', '%' . $filmingDate . '%')->first();

                  if(!$calendar)
                  {
                    $data[] = (object) [
                        'id'      => (int) $value->id,
                        'users_features_detail' => $users_features_detail,
                        'profilePhoto' => $photo,
                    ];
                  }
              }
            }
          }

          return  $data;

        }
        else
        {
          return response()->json(['error'=>'Dont have permision'] , 401 , []);
        }
      }
      else
      {
        return response()->json(['error'=>'Unauthorized'] , 401 , []);
      }

  }

  function searchTalents(Request $request, $id)
  {
    if($request->isJson())
    {
      //API token auth
      $user =  User::where('api_token', $request->header('Authorization'))->first();

      if($user)
      {
        $proposalUser = UsersBussinessProposal::where('id',$id)
        ->where('sender',$user->id)
        ->first();

        if($proposalUser)
        {
          $featuresProposal = FeaturesProposal::select('features_values_id')->where('users_bussiness_proposal_id',$id)->get();

          $line =  array();
          foreach ($featuresProposal as $key => $ll) 
          {
              $line[] = $ll->features_values_id;
          }

          $values = $line;
          
          if(count($values) > 0)
          {
            $ids = User::select('id')
            ->with(array('users_features_detail' => function($query) use ($values) {

            $query->select('users_features_detail.id','users_id','features_values_id','featureValue')
            ->join('features_values','features_values.id','=','users_features_detail.features_values_id')
            ->whereIn('users_features_detail.features_values_id', $values); 
            }))

            ->whereHas('users_features_detail', function($query) use ($values) {

            $query->select('users_features_detail.id','users_id','features_values_id','featureValue')
            ->join('features_values','features_values.id','=','users_features_detail.features_values_id')
            ->where('users_features_detail.features_values_id', $values); 

            })->get();


            if(!$ids)
            {
              return response()->json( ($ids->count()) ? $ids : [] , 200 );      
            }


            if(isset($data['date']))
            {
              foreach ($ids as $row) 
              {
                $calendar = UsersCalendar::where('users_id', $row->id)
                ->where('date', 'like', '%' . $data['date'] . '%')->first();

                if(!$calendar)
                {
                  $talentsIds[] = $row->id;
                }
              }
            }
            else
            {
              foreach ($ids as $row) 
              {
                $talentsIds[] = $row->id;
              }
            }

            if(!isset($talentsIds))
            {
              return response()->json(['status'=>'Not found'] , 200 ); 
            }


            $result = User::select('id')
            ->whereIn('id',$talentsIds)
            ->with(array('users_features_detail' => function($query){

            $query->select('users_features_detail.id','users_id','features_values_id','featureValue')
            ->join('features_values','features_values.id','=','users_features_detail.features_values_id');

            }))
            ->whereHas('users_features_detail', function ($query){

            $query->select('users_features_detail.id','users_id','features_values_id','featureValue')
            ->join('features_values','features_values.id','=','users_features_detail.features_values_id');

            })->get();

            return $result;
          }
          else
          {
            return response()->json(['error'=>'Length Required'] , 411 , []);
          }
        }
        else
        {
          return response()->json(['error'=>'Dont have permision'] , 401 , []);
        }
      }
      else
      {
        return response()->json(['error'=>'Unauthorized'] , 401 , []);
      }
    }
    else
    {
      return response()->json(['error','Bad Request'] , 400 , []);
    }
  }

  function search( Request $request )
  {
    if($request->isJson())
    {
      //API token auth
      $user =  User::where('api_token', $request->header('Authorization'))->first();

      if($user)
      {
        $data = $request->json()->all();
        $values = $data['features']; 

        if(count($values) > 0)
        {
          $ids = User::select('id')
          ->with(array('users_features_detail' => function($query) use ($values) {

          $query->select('users_features_detail.id','users_id','features_values_id','featureValue')
          ->join('features_values','features_values.id','=','users_features_detail.features_values_id')
          ->whereIn('users_features_detail.features_values_id', $values); 
          }))

          ->whereHas('users_features_detail', function($query) use ($values) {

          $query->select('users_features_detail.id','users_id','features_values_id','featureValue')
          ->join('features_values','features_values.id','=','users_features_detail.features_values_id')
          ->where('users_features_detail.features_values_id', $values); 

          })->get();


          if(!$ids)
          {
            return response()->json( ($ids->count()) ? $ids : [] , 200 );      
          }


          if(isset($data['date']))
          {
            foreach ($ids as $row) 
            {
              $calendar = UsersCalendar::where('users_id', $row->id)
              ->where('date', 'like', '%' . $data['date'] . '%')->first();

              if(!$calendar)
              {
                $talentsIds[] = $row->id;
              }
            }
          }
          else
          {
            foreach ($ids as $row) 
            {
              $talentsIds[] = $row->id;
            }
          }

          if(!isset($talentsIds))
          {
            return response()->json(['status'=>'Not found'] , 200 ); 
          }


          $result = User::select('id')
          ->whereIn('id',$talentsIds)
          ->with(array('users_features_detail' => function($query){

          $query->select('users_features_detail.id','users_id','features_values_id','featureValue')
          ->join('features_values','features_values.id','=','users_features_detail.features_values_id');

          }))
          ->whereHas('users_features_detail', function ($query){

          $query->select('users_features_detail.id','users_id','features_values_id','featureValue')
          ->join('features_values','features_values.id','=','users_features_detail.features_values_id');

          })->get();

          return $result;
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
    return response()->json(['error'=>'Bad Request'] , 400 , []);
  }

  function randomRange( $min, $max)
  {
    $min = ($min) ? (int) $min : 0;
    $max = ($max) ? (int) $max : PHP_INT_MAX;
    
    $range = range($min, $max);
    $average = array_sum($range) / count($range);
    
    $dist = array();
    for ($x = $min; $x <= $max; $x++) {
        $dist[$x] = -abs($average - $x) + $average + 1;
    }
    
    $map = array();
    foreach ($dist as $int => $quantity) {
        for ($x = 0; $x < $quantity; $x++) {
            $map[] = $int;
        }
    }
    
    shuffle($map);
    return current($map);

  }
}


