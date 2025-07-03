<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Media extends Model
{
    use HasFactory;

    // Kolom yang dapat diisi (mass assignable)
    protected $fillable = [
        'tutorial_id',
        'filename',
    ];

    /**
     * Relasi: Media milik satu Tutorial
     */
    public function tutorial()
    {
        return $this->belongsTo(Tutorial::class);
    }
}
