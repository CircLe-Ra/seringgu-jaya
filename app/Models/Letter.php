<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Letter extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function letter_type()
    {
        return $this->belongsTo(LetterType::class);
    }

    public function family_member()
    {
        return $this->belongsTo(FamilyMember::class);
    }

}
