<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    protected $guarded = ['id'];

    public function religion()
    {
        return $this->belongsTo(Religion::class);
    }

    public function education()
    {
        return $this->belongsTo(Education::class);
    }

    public function employment()
    {
        return $this->belongsTo(Employment::class);
    }

    public function blood_group()
    {
        return $this->belongsTo(BloodGroup::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function letters()
    {
        return $this->hasMany(Letter::class);
    }

    public function family_card()
    {
        return $this->belongsTo(FamilyCard::class);
    }
}
