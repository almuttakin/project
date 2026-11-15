<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Unit PKS
        $this->call(UnitPksSeeder::class);

        // 2. Seed Transaksi Timbangan & Log SAP
        $this->call(TransaksiTimbanganSeeder::class);

        // 3. Seed Aktor 1: Administrator TI Regional III
        User::updateOrCreate(
            ['username' => 'admin.ti.regional3'],
            [
                'name'     => 'Administrator TI Regional III',
                'email'    => 'admin.ti.regional3@ptpn4.co.id',
                'password' => Hash::make('admin123'),
                'role'     => 'Admin Regional',
            ]
        );

        // 4. Seed Aktor 2: Kepala TI PTPN Kantor Pusat (Jakarta)
        User::updateOrCreate(
            ['username' => 'kepala.ti.pusat'],
            [
                'name'     => 'Kepala Divisi TI Kantor Pusat Jakarta',
                'email'    => 'kepala.ti@ptpn.co.id',
                'password' => Hash::make('pusat123'),
                'role'     => 'Kepala TI Pusat',
            ]
        );

        // Fallback user admin pusat alias
        User::updateOrCreate(
            ['email' => 'admin@ptpn4.co.id'],
            [
                'name'     => 'Admin IT Kantor Pusat',
                'username' => 'admin',
                'password' => Hash::make('password123'),
                'role'     => 'Kepala TI Pusat',
            ]
        );
    }
}
