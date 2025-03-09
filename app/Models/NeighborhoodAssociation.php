<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NeighborhoodAssociation extends Model
{
    protected $guarded = ['id'];

    public function citizen()
    {
        return $this->belongsTo(CitizenAssociation::class, 'citizen_association_id');
    }

    public function user(){
       return $this->belongsTo(User::class);
    }

    public function family_cards()
    {
        return $this->hasMany(FamilyCard::class);
    }

    public function family_members()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function letters(){
        return $this->hasMany(Letter::class);
    }
}
