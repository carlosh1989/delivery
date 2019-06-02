<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class CompanyProfile extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
        protected $table = 'company_profile';
        public $timestamps = false;

       protected $fillable = [
        'logo','cover_page','path','company_name','latitude','longitude','address','enable_update','company_email','user_email','primary_phone','secondary_phone' 
    ];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

}
