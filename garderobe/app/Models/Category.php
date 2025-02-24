<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    /**
     * Get the clothing items for this category.
     */
    public function clothingItems()
    {
        return $this->hasMany(ClothingItem::class);
    }
}
