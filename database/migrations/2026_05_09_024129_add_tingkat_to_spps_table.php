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
    Schema::table('spps', function (Blueprint $table) {
        $table->enum('tingkat', ['X', 'XI', 'XII'])->after('kode')->nullable();
    });
    }

    public function down(): void
    {
    Schema::table('spps', function (Blueprint $table) {
        $table->dropColumn('tingkat');
    });
    }
};
