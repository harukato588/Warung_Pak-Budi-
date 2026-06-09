<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ⚠️ VULN #5: Mass Assignment — $guarded = [] berarti SEMUA field bisa diisi dari luar
// Attacker bisa POST field apapun termasuk user_id, is_admin, dll
class Product extends Model
{
    protected $guarded = []; // Tidak ada proteksi sama sekali!

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
