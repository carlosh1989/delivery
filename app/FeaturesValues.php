<?php

namespace App;

use App\FeaturesLabels;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class FeaturesValues extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'features_values';
    protected $fillable = [
        'featureValue','features_labels_id'
    ];


    // function features_labels(){
    //     // return $this->hasMany('App\FeaturesLabels')->select(array('id'));
    // }

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
     
    ];

    public function FeaturesLabels()
    {
        return $this->belongsTo(FeaturesLabels::class,'features_labels_id','id');
    }
}
