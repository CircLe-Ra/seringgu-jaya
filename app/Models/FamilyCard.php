<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyCard extends Model
{
    protected $guarded = ['id'];

    public function citizen()
    {
        return $this->belongsTo(CitizenAssociation::class, 'citizen_association_id');
    }

    public function neighborhood()
    {
        return $this->belongsTo(NeighborhoodAssociation::class, 'neighborhood_association_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function sub_district()
    {
        return $this->belongsTo(SubDistrict::class, 'sub_district_id');
    }

    public function family_members()
    {
        return $this->hasMany(FamilyMember::class);
    }

}
