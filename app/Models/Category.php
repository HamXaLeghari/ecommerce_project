<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $connection = "pgsql";

    protected $table = "category";
    protected $fillable = [
        "id",
        "name",
        "image_path",
        "description",
        "is_featured",
        "is_active"
    ];
    public function getImagePathAttribute($value){
        return url($value);
    }

    public $timestamps = false;
    public function products(): HasMany
    {
       return $this->hasMany(Product::class);
    }
}
