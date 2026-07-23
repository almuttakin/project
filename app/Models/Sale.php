<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    // Izinkan kolom 'amount' dan 'created_at' diisi secara mass assignment
    protected $fillable = ['amount', 'created_at'];

    // ATAU cara cepat lainnya (meng-un-guard semua kolom):
    // protected $guarded = [];
}