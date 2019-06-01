<?php

namespace App\Http\Controllers;

/* Models */
use App\CompanyProfile;
use App\CompanyUsers;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\AuthController;
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

class CompanyUsersController extends Controller
{
    public function companies(Request $request)
    {
      $data_session = AccessController::Verify($request);

      $role = $data_session['userType'];

      if($role == 1)
      {
        $companies = CompanyProfile::paginate(10);
        return $companies;
      }
      else
      {
        return response()->json(['error'=>'Unauthorized'] , 401 , []);
      }
    }

    public function companies_list(Request $request)
    {
        $data_session = AccessController::Verify($request);

        $data = $request->json()->all();

        /*
        $companies = CompanyUsers::where('user_id', $data['user_id'])
        ->where('enable_user',1)
        ->get();

        $companies_data = array();

        foreach ($companies as $key => $company) 
        {
        	$companies_data[] = array(
        		'company_profile_id' => $company->company_profile_id,
        		'user_type' => $company->user_type,
        		'enable_user' => $company->enable_user,
        		'company_name' => $company->company_profile->company_name,
        	);
        }

        */

      

    $companies = CompanyUsers::
    select('company_users.company_profile_id','company_profile.id','company_profile.company_name','user_id')
    ->where('enable_user',1)
    ->where('user_id', $data_session['id'])
    ->join('company_profile','company_profile.id','=','company_users.company_profile_id')
    ->groupBy('company_profile.id')
    ->get();


     //$companies = CompanyProfile::all(['id','company_name']);
        $companies_data = array();
        
        foreach ($companies as $key => $company) 
        {
          $type_employee = CompanyUsers::
          select('company_users.company_profile_id','user_id','user_type')
          ->where('enable_user',1)
          ->where('user_id', $data_session['id'])
          ->where('company_profile_id',$company->company_profile_id)
          ->where('user_id',$company->user_id)
          ->get()->toArray();

          //print_r($type_employee);
          $roles = [];

          foreach ($type_employee as $key => $type) 
          {   
              
              $roles[] = $type['user_type'];
          }


          $companies_data[] = $company;
          $company['roles'] = $roles;

        }



        return response()->json($companies_data, 200);
    }

    public function usersAllcompany(Request $request, $id)
    {
        //AccessController::Verify($request);

    	$employee = CompanyUsers::where('company_profile_id',$id)->first();

    	if($employee)
    	{
    		$employees = CompanyUsers::where('company_profile_id',$id)->get();
    		$employee_data = array();

    		foreach ($employees as $key => $employee) 
    		{
    			$employee_data[] = array(
    				'user_id' => $employee->user_id,
    				'user_type' => $employee->user_type,
    				'name' => $employee->user_data->name
    			);
    		}

    		return response()->json($employee_data, 200);
    	}
    	else
    	{
    		return response()->json(['status' => 'Not employee found!'], 411);
    	}
    }

    public function usersAllcompanies(Request $request)
    {
        //AccessController::Verify($request);

		$employees = CompanyUsers::all();
		$employee_data = array();

		foreach ($employees as $key => $employee) 
		{
			$employee_data[] = array(
				'user_id' => $employee->user_id,
				'name' => $employee->user_data->name
			);
		}

		return response()->json($employee_data, 200);
    }

    public function addEmployee(Request $request)
    {
        //AccessController::Verify($request);

        $data = $request->json()->all();

        $employee = CompanyUsers::where('user_id',$data['user_id'])
        ->where('company_profile_id',$data['company_profile_id'])
        ->where('user_type', $data['user_type'])
        ->first();

        if($employee)
        {
        	return response()->json(['status'=>'Employee is already in that position in the company']);
        }
        else
        {
	        $create_user = CompanyUsers::create([
	        	'user_id' => $data['user_id'],
	        	'company_profile_id'  => $data['company_profile_id'],
	        	'user_type' => $data['user_type'],
	        	'enable_user' => $data['enable_user']
	        ]);
        }

        return response()->json($create_user, 201);
    }

    public function allowedAccess(Request $request)
    {
        $data = $request->json()->all();

       	$admin = CompanyUsers::where('user_id', $data['admin_id'])
       	->where('user_type', 1)
       	->first();

       	if(!$admin)
       	{
       		return response()->json(['status'=>'Not admin found!'], 400);
       	}

       	$employee = CompanyUsers::where('user_id', $data['employee_id'])
       	->where('user_type', $data['user_type'])
       	->first();

       	if(!$employee)
       	{
       		return response(['status' => 'Employee not found!'], 400)->json();
       	}
       	else
       	{
       		//si enable is true
       		if($employee->enable_user == 1)
       		{
       			$employee->enable_user = 0;
       		}
       		else
       		{
       			$employee->enable_user = 1;
       		}

       		$employee->save();
       	}

       	return response()->json(['status' => 'status employee changed!','employee'=>$employee, 201]);
    }

    public function employeesCount(Request $request,$id)
    {
        $company = CompanyUsers::where('company_profile_id',$id)->first();

        if(!$company)
        {
          return response()->json(['status'=>'Not found company users!']);
        }

        $employees = CompanyUsers::where('company_profile_id',$id)->where('enable_user',1)
        ->groupBy('user_id')
        ->groupBy('company_profile_id')
        ->get();

        return $employees->count();
    }

    public function companyEmployeeRoles(Request $request, $id)
    {
      $data_session = AccessController::Verify($request);
      
      $employee_roles = CompanyUsers::where('user_id', $data_session->id)
      ->where('company_profile_id',$id)
      ->where('enable_user',1)
      ->first();

      if($employee_roles)
      {
        $employee_roles = CompanyUsers::where('user_id', $data_session->id)
        ->where('company_profile_id',$id)
        ->where('enable_user',1)
        ->get(['user_type']);

        return $employee_roles;
      }
      else
      {
        return response()->json(['status'=>'Employee not found!'], 400);
      }
    }
}
