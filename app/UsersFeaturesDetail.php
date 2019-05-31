<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class UsersFeaturesDetail extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users_features_detail';
    public $timestamps = false;
    protected $fillable = [
        'users_id','features_values_id'
    ];


    // function users_features_detail(){
    //     return $this->hasMany('App\Users');
    // }


    // function features_labels(){
    //     // return $this->hasMany('App\FeaturesLabels')->select(array('id'));
    // }

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        // 'hidden_row',
    ];
}
