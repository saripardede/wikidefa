<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reward extends Model
{
    use HasFactory;

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'user_id',
        'judul',
        'deskripsi',
        'poin',
        'tutorial_id', // pastikan kolom ini ada di tabel rewards jika ingin relasi ke tutorial
    ];

    /**
     * Relasi: Reward dimiliki oleh satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    /**
     * Relasi: Reward terkait dengan satu tutorial
     */
    public function tutorial()
    {
            return $this->belongsTo(Tutorial::class);
    }
}
