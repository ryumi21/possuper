<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Insert Role Permission if not exists
        $existsRolePermission = DB::table('menu')->where('Fitur', 'Role Permission')->exists();
        if (!$existsRolePermission) {
            DB::table('menu')->insert([
                'Category' => 'Akses & Role',
                'Fitur' => 'Role Permission',
                'IsPos' => 1,
                'Is_Active' => 1
            ]);
        }

        // Insert Roles if not exists
        $existsRoles = DB::table('menu')->where('Fitur', 'Roles')->exists();
        if (!$existsRoles) {
            DB::table('menu')->insert([
                'Category' => 'Akses & Role',
                'Fitur' => 'Roles',
                'IsPos' => 1,
                'Is_Active' => 1
            ]);
        }
    }

    public function down(): void
    {
        DB::table('menu')->whereIn('Fitur', ['Role Permission', 'Roles'])->delete();
    }
};
