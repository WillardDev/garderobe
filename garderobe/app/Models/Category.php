<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ClothingItem;

class Category extends Model
{
    protected $fillable = ['name', 'description'];

    public function clothingItems()
    {
        return $this->hasMany(ClothingItem::class);
    }
}
