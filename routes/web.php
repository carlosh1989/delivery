<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/holamundo', function () use ($router) {
    return ['1','2','3'];
});

$router->post('auth/user/register', ['uses' => 'AuthController@register']);
$router->post('auth/user/login', ['uses' => 'AuthController@login']);
$router->get('auth/user/image', ['uses' => 'AuthController@image']);
$router->post('auth/user/image', ['uses' => 'AuthController@image']);

//companies
$router->post('users/companies', ['uses' => 'CompanyUsersController@companies_list']);
$router->get('users/company/{id}', ['uses' => 'CompanyUsersController@usersAllcompany']);
$router->get('users/companies', ['uses' => 'CompanyUsersController@usersAllcompanies']);

//user company
$router->post('company/user', ['uses' => 'CompanyUsersController@addEmployee']);
$router->post('company/user/allowedAccess', ['uses' => 'CompanyUsersController@allowedAccess']);

$router->get('company/employee/roles/{id}', ['uses' => 'CompanyUsersController@companyEmployeeRoles']);



//user company dashboard admin
$router->get('company/employees/count/{id}', ['uses' => 'CompanyUsersController@employeesCount']);

//products
$router->post('product/create', ['uses' => 'ProductController@create']);
$router->post('product/create2', ['uses' => 'ProductController@create2']);
$router->get('products/company/{id}', ['uses' => 'ProductController@productsCompany']);


//cloudinary
$router->post('image/upload',['uses'=> 'ImageController@upload']);

//files
$router->post('file/upload', ['uses' => 'FileController@upload']);


$router->post('auth/attemp',['uses'=>'AuthController@getToken']);
$router->post('auth/logout',['uses'=>'AuthController@logout']);
$router->post('auth/recover',['uses'=>'AuthController@sendRecoverAccount']);
$router->post('auth/recover/attemp',['uses'=>'AuthController@recoverAttemp']);
$router->post('auth/recover/verify',['uses'=>'AuthController@verifyRecoverAccount']);
$router->get('auth/activate/{id}/{email_token}',['uses'=>'AuthController@activateAccount']);


