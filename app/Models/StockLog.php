<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    protected $fillable = ['product_id', 'qty_added', 'stock_before', 'stock_after', 'admin_name', 'description'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
