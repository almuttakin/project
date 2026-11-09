<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatLogin extends Model
{
    use HasFactory;

    protected $table = 'riwayat_logins';

    protected $fillable = [
        'user_id',
        'nama_pengguna',
        'username',
        'role',
        'ip_address',
        'user_agent',
        'login_at',
        'logout_at',
        'status',
    ];

    protected $casts = [
        'login_at'  => 'datetime',
        'logout_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

