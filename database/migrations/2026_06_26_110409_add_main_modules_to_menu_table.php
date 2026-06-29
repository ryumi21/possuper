<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Insert Mesin Kasir (POS) if not exists
        $existsPos = DB::table('menu')->where('Fitur', 'Mesin Kasir (POS)')->exists();
        if (!$existsPos) {
            DB::table('menu')->insert([
                'Category' => 'Transaksi & Order',
                'Fitur' => 'Mesin Kasir (POS)',
                'IsPos' => 1,
                'Is_Active' => 1
            ]);
        }

        // Insert Log Transaksi if not exists
        $existsTx = DB::table('menu')->where('Fitur', 'Log Transaksi')->exists();
        if (!$existsTx) {
            DB::table('menu')->insert([
                'Category' => 'Transaksi & Order',
                'Fitur' => 'Log Transaksi',
                'IsPos' => 1,
                'Is_Active' => 1
            ]);
        }

        // Insert Users if not exists
        $existsUsers = DB::table('menu')->where('Fitur', 'Users')->exists();
        if (!$existsUsers) {
            DB::table('menu')->insert([
                'Category' => 'Akses & Role',
                'Fitur' => 'Users',
                'IsPos' => 1,
                'Is_Active' => 1
            ]);
        }
    }

    public function down(): void
    {
        DB::table('menu')->whereIn('Fitur', ['Mesin Kasir (POS)', 'Log Transaksi', 'Users'])->delete();
    }
};
