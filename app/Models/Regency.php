<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    protected $guarded = ['id'];

    public function family_cards()
    {
        return $this->hasMany(FamilyCard::class);
    }
}
