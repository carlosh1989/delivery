<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class FeaturesLabels extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'features_labels';
    protected $fillable = [
        'featureLabel','features_categories_id',
    ];


    function features_values(){
        return $this->hasMany('App\FeaturesValues');
    }

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        // 'hidden_row',
    ];

    public function FeaturesCategories()
    {
        return $this->belongsTo(FeaturesCategories::class,'features_categories_id','id');
    }
}
