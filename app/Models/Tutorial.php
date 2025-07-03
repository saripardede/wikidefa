<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutorial extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'tutorials';

    protected $fillable = ['judul', 'isi', 'kategori', 'status', 'user_id', 'video_url', 'image_path'];

    public static $status = [
        'pending',
        'approved',
        'rejected',
        'revision',
    ];

    // ✅ Event model untuk reset poin reward saat status diubah ke "revision"
    protected static function booted()
    {
        static::updating(function ($tutorial) {
    // Jika status berubah dari approved ke revision → reset poin
    if (
        $tutorial->isDirty('status') &&
        $tutorial->getOriginal('status') === 'approved' &&
        $tutorial->status === 'revision'
    ) {
        $tutorial->rewards()->update(['poin' => 0]);
    }

    // Jika status berubah menjadi pending → hapus catatan revisi
    if (
        $tutorial->isDirty('status') &&
        $tutorial->status === 'pending'
    ) {
        $tutorial->catatan_revisi = null;
    }
});

    }

    // Relasi: Tutorial memiliki banyak media (gambar/video)
    public function media()
    {
        return $this->hasMany(Media::class);
    }

    // Relasi: Tutorial dimiliki oleh satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Tutorial memiliki banyak reward
    public function rewards()
    {
        return $this->hasMany(Reward::class);
    }
}
