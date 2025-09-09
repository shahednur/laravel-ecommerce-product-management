<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
     use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Auto-generate slug when creating category
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * A category has many products
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }
}
