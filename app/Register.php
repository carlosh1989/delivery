<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Register extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';
    protected $fillable = [
        'name', 'lastName', 'email', 'email_token', 'password', 'securityAnswer', 'securityQuestion', 'verified', 'userType', 'remember_token', 'api_token', 'birthDateMonth', 'birthDateDay', 'birthDateYear', 'originCountry', 'actualCountry', 'nationality', 'otherNationality', 'primaryPhone', 'secondaryPhone', 'avatar', 'pathAvatar', 'passport', 'identificationCard', 'address', 'sector', 'city', 'province', 'preferred_role', 'preferred_production' ,'created_at', 'updated_at'
    ];

/**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];




    function userExist($email)
    {
        return $this->where('email',$email)->get()->first();
    }


    function isTalent($token)
    {
        return $this->where( 'api_token' , $token )->get()->first();
    }



}
