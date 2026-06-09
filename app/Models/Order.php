<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ⚠️ VULN #5: Mass Assignment — tidak ada $fillable, semua field bisa dimanipulasi
class Order extends Model
{
    protected $guarded = []; // Tidak ada proteksi!

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
