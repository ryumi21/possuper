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
        $retailMenus = [
            ['Category' => 'Produk & Menu', 'Fitur' => 'Produk & Kategori'],
            ['Category' => 'Transaksi & Order', 'Fitur' => 'Multi-Payment'],
            ['Category' => 'Transaksi & Order', 'Fitur' => 'Barcode Scanning'],
            ['Category' => 'Transaksi & Order', 'Fitur' => 'Refund / Return / Exchange'],
            ['Category' => 'Inventori & Stok', 'Fitur' => 'Update Stok Real-Time'],
            ['Category' => 'Inventori & Stok', 'Fitur' => 'Supplier & Purchase Order'],
            ['Category' => 'Laporan & Analitik', 'Fitur' => 'Penjualan & Menu Laris'],
            ['Category' => 'Laporan & Analitik', 'Fitur' => 'Laporan Penjualan'],
            ['Category' => 'Integrasi & Multi-Outlet', 'Fitur' => 'Integrasi Aplikasi'],
            ['Category' => 'Integrasi & Multi-Outlet', 'Fitur' => 'Sinkronisasi Outlet'],
            ['Category' => 'Produk & Menu', 'Fitur' => 'Satuan Barang'],
            ['Category' => 'Inventori & Stok', 'Fitur' => 'Bahan Baku & HPP'],
            ['Category' => 'Akses & Role', 'Fitur' => 'Role Permission'],
            ['Category' => 'Akses & Role', 'Fitur' => 'Roles'],
            ['Category' => 'Transaksi & Order', 'Fitur' => 'Mesin Kasir (POS)'],
            ['Category' => 'Transaksi & Order', 'Fitur' => 'Log Transaksi'],
            ['Category' => 'Akses & Role', 'Fitur' => 'Users'],
        ];

        $cafeMenus = [
            ['Category' => 'Produk & Menu', 'Fitur' => 'Menu & Item'],
            ['Category' => 'Transaksi & Order', 'Fitur' => 'Table Management'],
            ['Category' => 'Transaksi & Order', 'Fitur' => 'Kitchen Display System'],
            ['Category' => 'Transaksi & Order', 'Fitur' => 'Self-Order QR Code'],
            ['Category' => 'Loyalty & Pelanggan', 'Fitur' => 'Loyalty Program'],
            ['Category' => 'Loyalty & Pelanggan', 'Fitur' => 'Customer Data & Loyalty'],
            ['Category' => 'Inventori & Stok', 'Fitur' => 'Bahan Baku & HPP'],
            ['Category' => 'Produk & Menu', 'Fitur' => 'Satuan Barang'],
            ['Category' => 'Laporan & Analitik', 'Fitur' => 'Laporan Penjualan'],
            ['Category' => 'Akses & Role', 'Fitur' => 'Role Permission'],
            ['Category' => 'Akses & Role', 'Fitur' => 'Roles'],
            ['Category' => 'Transaksi & Order', 'Fitur' => 'Mesin Kasir (POS)'],
            ['Category' => 'Transaksi & Order', 'Fitur' => 'Log Transaksi'],
            ['Category' => 'Akses & Role', 'Fitur' => 'Users'],
        ];

        // Kosongkan tabel dulu jika mau update bersih
        DB::table('menu')->truncate();

        foreach ($retailMenus as $menu) {
            DB::table('menu')->insert([
                'Category' => $menu['Category'],
                'Fitur' => $menu['Fitur'],
                'IsPos' => 1,
                'Is_Active' => in_array($menu['Fitur'], ['Produk & Kategori', 'Multi-Payment', 'Barcode Scanning', 'Refund / Return / Exchange', 'Laporan Penjualan', 'Satuan Barang', 'Bahan Baku & HPP', 'Role Permission', 'Roles', 'Mesin Kasir (POS)', 'Log Transaksi', 'Users']) ? 1 : 0,
            ]);
        }

        foreach ($cafeMenus as $menu) {
            DB::table('menu')->insert([
                'Category' => $menu['Category'],
                'Fitur' => $menu['Fitur'],
                'IsPos' => 2,
                'Is_Active' => in_array($menu['Fitur'], ['Menu & Item', 'Kitchen Display System', 'Bahan Baku & HPP', 'Satuan Barang', 'Laporan Penjualan', 'Role Permission', 'Roles', 'Mesin Kasir (POS)', 'Log Transaksi', 'Users']) ? 1 : 0,
            ]);
        }
    }
}
