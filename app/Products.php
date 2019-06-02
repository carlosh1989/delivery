<?php

namespace App;

use App\CompanyProfile;
use App\ProductsGallery;
use App\User;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Products extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
        protected $table = 'products';
        public $timestamps = false;
       protected $fillable = [
        'product_name','price','enable','company_profile_id','tax' 
    ];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];


    public function products_gallery()
    {
        return $this->hasMany(ProductsGallery::class,'products_id','id');
    }

    function users_features_detail(){
        return $this->hasMany('App\UsersFeaturesDetail', 'users_id');
    }

    function userExist($email){
        return $this->where('email',$email)->get()->first();
    }

    public function company_profile()
    {
        return $this->belongsTo(CompanyProfile::class,'company_profile_id','id');
    }

    public function user_data()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
