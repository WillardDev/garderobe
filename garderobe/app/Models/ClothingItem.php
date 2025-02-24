<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;

class ClothingItem extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'description',
        'color',
        'size',
        'brand',
        'image_path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
