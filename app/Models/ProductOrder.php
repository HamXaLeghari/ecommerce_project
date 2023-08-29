<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductOrder extends Model
{
    use HasFactory;

    public $table = "product_orders";
    protected $fillable = ["id","product_id","order_id","quantity","sub_total","created_at","updated_at"];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, "order_id");
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, "product_id");
    }
}
