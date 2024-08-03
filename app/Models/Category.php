<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name', 'slug', 'subcategory_count', 'product_count', 'status'];
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($category) {
            if ($category->isDirty('status')) {
                //update status của subcategories
                $category->subcategories()->update(['status' => $category->status]);
                //update status của products
                $category->products()->update(['status' => $category->status]);
            }
        });
    }

    
}
