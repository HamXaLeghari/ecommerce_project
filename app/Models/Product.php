<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $connection = "pgsql";

    protected $table = "product";

    protected $fillable = [
        "id",
        "title",
        "image",
        "description",
        "price",
        "stock_quantity",
        "is_featured",
        "is_available",
        "created_at",
        "updated_at",
        "category_id"
    ];

    public function getImageAttribute($value){
        return url($value);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class,"category_id");
    }

    public function productOrders(): HasMany
    {
        return $this->hasMany(ProductOrder::class);
    }
}
