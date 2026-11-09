<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TransaksiTimbangan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terikat dengan model ini.
     *
     * @var string
     */
    protected $table = 'transaksi_timbangan';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nota_no',
        'no_plat',
        'unit_pks_id',
        'jam_masuk',
        'jam_keluar',
        'gross',
        'tarra',
        'netto',
        'status_timbangan',
        'xml_status',
        'status_sap',
        'sap_sent_at',
        'sap_doc_no',
    ];

    /**
     * Attribute casting.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gross' => 'float',
            'tarra' => 'float',
            'netto' => 'float',
            'sap_sent_at' => 'datetime',
        ];
    }

    /**
     * Virtual attributes to append for compatibility with views and XML generators.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'pks_loc',
        'vpn_ip',
        'status_vpn',
        'xml_generated_at',
    ];

    /**
     * Accessor untuk nama lokasi unit PKS.
     */
    public function getPksLocAttribute(): string
    {
        return $this->unitPks ? $this->unitPks->nama_unit : '-';
    }

    /**
     * Accessor untuk IP VPN unit PKS.
     */
    public function getVpnIpAttribute(): string
    {
        return $this->unitPks ? ($this->unitPks->ip_vpn ?? '-') : '-';
    }

    /**
     * Accessor untuk status VPN unit PKS.
     */
    public function getStatusVpnAttribute(): string
    {
        if (!$this->unitPks) {
            return 'Connected';
        }
        return $this->unitPks->status_vpn === 'Trouble' ? 'Trouble' : 'Connected';
    }

    /**
     * Accessor untuk format tanggal XML generated.
     */
    public function getXmlGeneratedAtAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : date('Y-m-d H:i:s');
    }

    /**
     * Accessor status_sap agar konsisten huruf besar ('SENT' / 'PENDING')
     * untuk kompabilitas tampilan badge Blade dan skema XML SAP.
     */
    public function getStatusSapAttribute($value): string
    {
        return strtoupper($value ?? 'Pending');
    }

    /**
     * Mutator status_sap agar tersimpan sesuai enum database ('Sent' / 'Pending' / 'Failed').
     */
    public function setStatusSapAttribute($value): void
    {
        $this->attributes['status_sap'] = ucfirst(strtolower($value ?? 'Pending'));
    }

    /**
     * Relasi ke UnitPks.
     * Setiap transaksi timbangan merujuk pada satu unit PKS (belongsTo).
     */
    public function unitPks(): BelongsTo
    {
        return $this->belongsTo(UnitPks::class, 'unit_pks_id');
    }

    /**
     * Relasi riwayat LogIntegrasiSap.
     * Satu transaksi timbangan dapat memiliki banyak riwayat pengiriman log ke SAP (hasMany).
     */
    public function logIntegrasiSaps(): HasMany
    {
        return $this->hasMany(LogIntegrasiSap::class, 'transaksi_id');
    }

    /**
     * Relasi ke LogIntegrasiSap utama / paling akhir (hasOne).
     * Berguna untuk menampilkan status log terakhir dari transaksi ini.
     */
    public function logIntegrasiSap(): HasOne
    {
        return $this->hasOne(LogIntegrasiSap::class, 'transaksi_id')->latestOfMany();
    }
}
