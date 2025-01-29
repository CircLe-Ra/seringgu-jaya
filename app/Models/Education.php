<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $guarded = ['id'];

    public function family_members(){
        return $this->hasMany(FamilyMember::class);
    }
}
