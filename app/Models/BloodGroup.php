<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodGroup extends Model
{
    protected $guarded = ['id'];

    public function family_members(){
        return $this->hasMany(FamilyMember::class);
    }
}
