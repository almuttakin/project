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

        // 3. Seed Akun Admin Contoh (sesuai spesifikasi Task 3)
        User::updateOrCreate(
            ['email' => 'admin.ti.regional3@ptpn4.co.id'],
            [
                'name'     => 'Administrator TI Regional III',
                'username' => 'admin.ti.regional3',
                'password' => Hash::make('admin123'),
                'role'     => 'Admin',
            ]
        );

        // 4. Seed Pengguna Default Tambahan (Operator & Admin IT Pusat)
        User::updateOrCreate(
            ['email' => 'operator@ptpn4.co.id'],
            [
                'name'     => 'Operator Timbangan PTPN 4',
                'username' => 'operator',
                'password' => Hash::make('password123'),
                'role'     => 'Operator',
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@ptpn4.co.id'],
            [
                'name'     => 'Admin IT Kantor Pusat',
                'username' => 'admin',
                'password' => Hash::make('password123'),
                'role'     => 'Admin',
            ]
        );
    }
}
