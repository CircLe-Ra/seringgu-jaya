<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
    protected $guarded = ['id'];

    public function news()
    {
        return $this->hasMany(News::class);
    }
}
