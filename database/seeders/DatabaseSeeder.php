<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun admin default
        User::updateOrCreate(
            ['email' => 'admin@sigap.test'],
            [
                'name' => 'Admin Gudang',
                'password' => Hash::make('password'),
            ]
        );

        // Beberapa contoh barang
        $contoh = [
            ['nama_barang' => 'Laptop Asus X415', 'jenis' => 'Elektronik', 'stok' => 12, 'deskripsi' => 'Laptop kerja standar.'],
            ['nama_barang' => 'Kursi Kantor', 'jenis' => 'Furniture', 'stok' => 25, 'deskripsi' => 'Kursi ergonomis.'],
            ['nama_barang' => 'Printer Epson L3210', 'jenis' => 'Elektronik', 'stok' => 4, 'deskripsi' => 'Printer multifungsi.'],
            ['nama_barang' => 'Kertas A4 80gr', 'jenis' => 'ATK', 'stok' => 50, 'deskripsi' => '1 rim isi 500 lembar.'],
            ['nama_barang' => 'Spidol Whiteboard', 'jenis' => 'ATK', 'stok' => 3, 'deskripsi' => 'Hitam, refillable.'],
        ];

        foreach ($contoh as $data) {
            Barang::firstOrCreate(['nama_barang' => $data['nama_barang']], $data);
        }
    }
}
