<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function categories()
    {
        return $this->belongsToMany(NewsCategory::class, 'category_in_news', 'news_id', 'news_category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(TagInNews::class, 'tag_in_news', 'news_id', 'tag');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
