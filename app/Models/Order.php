<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'province',
        'city',
        'district',
        'village',
        'postal_code',
        'address',
        'total_price',
        'status',
        'payment_method',
        'tracking_number',
        'snap_token'
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Tambahkan fungsi terpisah untuk items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
