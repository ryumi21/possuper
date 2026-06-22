<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('food', function (Blueprint $table) {
            $table->string('image')->nullable()->after('Name');
        });
        Schema::table('product', function (Blueprint $table) {
            $table->string('image')->nullable()->after('Name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('food', function (Blueprint $table) {
            $table->dropColumn('image');
        });
        Schema::table('product', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
