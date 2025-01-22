<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CitizenAssociation extends Model
{
    protected $guarded = ['id'];

    public function neighborhoods()
    {
        return $this->hasMany(NeighborhoodAssociation::class);
    }

    public function family_cards()
    {
        return $this->hasMany(FamilyCard::class);
    }
}
