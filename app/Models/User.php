<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    const UPDATED_AT = null; // Disable updated_at

    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'role',
        'posisi',
        'password',
        'last_login',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function rewards()
    {
        return $this->hasMany(\App\Models\Reward::class);
    }
    
    public function tutorials()
    {
        return $this->hasMany(\App\Models\Tutorial::class);
    }    

    public function getTotalPoinAttribute()
    {
        return $this->rewards()->sum('poin');
    }

    public function getLevelAttribute()
{
    $score = $this->skor ?? 0;

    if ($score >= 2001) return 'Lv.7 (★★★★★)';
    if ($score >= 1501) return 'Lv.6 (★★★★)';
    if ($score >= 1001) return 'Lv.5 (★★★)';
    if ($score >= 501)  return 'Lv.4 (★★★)';
    if ($score >= 301)  return 'Lv.3 (★★★)';
    if ($score >= 101)  return 'Lv.2 (★★)';
    if ($score >= 0)    return 'Lv.1 (★)';

    return 'Lv.0';
}

    
}
