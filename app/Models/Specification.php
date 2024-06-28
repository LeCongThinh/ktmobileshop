<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Specification extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['title','content','product_id'];
    public function products()
    {
        return $this->belongsTo(Product::class);
    }
}