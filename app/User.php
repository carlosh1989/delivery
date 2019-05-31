<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
       protected $fillable = [
        'name', 'lastName', 'email', 'email_token', 'password', 'securityAnswer', 'securityQuestion', 'verified', 'userType', 'remember_token', 'api_token', 'birthDateMonth', 'birthDateDay', 'birthDateYear', 'originCountry', 'actualCountry', 'nationality', 'otherNationality', 'avatar', 'pathAvatar', 'passport', 'identificationCard', 'address', 'sector', 'city', 'province','profilePhoto','preferred_role','preferred_production','created_at', 'updated_at'
    ];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];


    function users_features_detail(){
        return $this->hasMany('App\UsersFeaturesDetail', 'users_id');
    }

    function userExist($email){
        return $this->where('email',$email)->get()->first();
    }
}
