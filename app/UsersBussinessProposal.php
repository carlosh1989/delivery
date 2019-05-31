<?php

namespace App;

use App\Costume;
use App\ProposalLines;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class UsersBussinessProposal extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users_bussiness_proposal';
    protected $fillable = [
        'sender','agency_id','proposalType','title','description','initialPrice','castingType', 'rolType', 'filmingDate', 'hoursFilming', 'productionType', 'filmingCity', 'filmingAddress', 'filmingCoords','asistensePrice','assistanceDescription'
    ];

                   
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        // 'hidden_row',
    ];

    public function ProposalLines()
    {
        return $this->hasMany(ProposalLines::class, 'users_bussiness_proposal_id', 'id');
    }

    public function FeaturesProposal()
    {
        return $this->hasMany(FeaturesProposal::class, 'users_bussiness_proposal_id', 'id');
    }

    public function Costume()
    {
        return $this->hasMany(Costume::class, 'users_bussiness_proposal_id', 'id');
    }
}
