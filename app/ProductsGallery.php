<?php

namespace App;

use App\CompanyProfile;
use App\Products;
use App\ProductsGallery;
use App\User;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class ProductsGallery extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
        protected $table = 'products_gallery';
        public $timestamps = false;
       protected $fillable = [
        'products_id','image' 
    ];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function products()
    {
        return $this->belongsTo(Products::class,'products_id','id');
    }
}
