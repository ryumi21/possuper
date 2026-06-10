<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            ['Category' => 'Produk & Menu', 'Fitur' => 'Menu & Item'],
            ['Category' => 'Produk & Menu', 'Fitur' => 'Produk & Kategori'],
            
            ['Category' => 'Transaksi & Order', 'Fitur' => 'Table Management'],
            ['Category' => 'Transaksi & Order', 'Fitur' => 'Kitchen Display System'],
            ['Category' => 'Transaksi & Order', 'Fitur' => 'Self-Order QR Code'],
            ['Category' => 'Transaksi & Order', 'Fitur' => 'Multi-Payment'],
            ['Category' => 'Transaksi & Order', 'Fitur' => 'Barcode Scanning'],
            ['Category' => 'Transaksi & Order', 'Fitur' => 'Refund / Return / Exchange'],
            
            ['Category' => 'Loyalty & Pelanggan', 'Fitur' => 'Loyalty Program'],
            ['Category' => 'Loyalty & Pelanggan', 'Fitur' => 'Customer Data & Loyalty'],
            
            ['Category' => 'Inventori & Stok', 'Fitur' => 'Bahan Baku & HPP'],
            ['Category' => 'Inventori & Stok', 'Fitur' => 'Update Stok Real-Time'],
            ['Category' => 'Inventori & Stok', 'Fitur' => 'Supplier & Purchase Order'],
            
            ['Category' => 'Laporan & Analitik', 'Fitur' => 'Penjualan & Menu Laris'],
            ['Category' => 'Laporan & Analitik', 'Fitur' => 'Laporan Penjualan'],
            
            ['Category' => 'Integrasi & Multi-Outlet', 'Fitur' => 'Integrasi Aplikasi'],
            ['Category' => 'Integrasi & Multi-Outlet', 'Fitur' => 'Sinkronisasi Outlet'],
        ];

        // Kosongkan tabel dulu jika mau update bersih
        DB::table('menu')->truncate();

        foreach ($menus as $menu) {
            DB::table('menu')->insert([
                'Category' => $menu['Category'],
                'Fitur' => $menu['Fitur'],
                'IsPos' => 1,
            ]);
        }
    }
}
