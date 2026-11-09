<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogIntegrasiSap extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terikat dengan model ini.
     *
     * @var string
     */
    protected $table = 'log_integrasi_sap';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transaksi_id',
        'no_referensi_sap',
        'waktu_kirim',
        'status_respons',
        'kode_http',
    ];

    /**
     * Attribute casting.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'waktu_kirim' => 'datetime',
        ];
    }

    /**
     * Relasi ke TransaksiTimbangan.
     * Setiap baris log integrasi merujuk pada satu transaksi timbangan (belongsTo).
     */
    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(TransaksiTimbangan::class, 'transaksi_id');
    }
}
