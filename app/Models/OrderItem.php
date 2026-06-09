<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ⚠️ VULN #5: Mass Assignment — semua field bisa diisi dari request
class OrderItem extends Model
{
    protected $guarded = []; // Tidak ada proteksi!

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
