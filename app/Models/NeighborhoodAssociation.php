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
}
