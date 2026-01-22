<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    // Menentukan kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'user_id',
        'kos_id',
        'status',
        'total_harga',
        'payment_deadline',
        'reminded_at',
    ];

    /**
     * Relasi ke User: Booking ini punya siapa?
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Kos: Kamar mana yang di-booking?
     */
    public function kos(): BelongsTo
    {
        return $this->belongsTo(Kos::class);
    }
}