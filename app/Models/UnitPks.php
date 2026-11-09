<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitPks extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terikat dengan model ini.
     *
     * @var string
     */
    protected $table = 'unit_pks';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_unit',
        'lokasi',
        'ip_vpn',
        'ip_wifi',
        'status_vpn',
        'status_wifi',
        'lat',
        'lng',
    ];

    /**
     * Relasi ke TransaksiTimbangan.
     * Satu unit PKS memiliki banyak transaksi timbangan.
     */
    public function transaksiTimbangans(): HasMany
    {
        return $this->hasMany(TransaksiTimbangan::class, 'unit_pks_id');
    }
}
