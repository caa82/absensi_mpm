<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add notula column to agenda_rapat table
        Schema::table('agenda_rapat', function (Blueprint $table) {
            $table->text('notula')->nullable()->after('lokasi');
        });

        // Add bukti_foto column to absensi table
        Schema::table('absensi', function (Blueprint $table) {
            $table->string('bukti_foto')->nullable()->after('keterangan');
        });

        // Insert new status: Sakit
        DB::table('status_absensi')->insert([
            'nama_status' => 'Sakit',
            'bobot_kehadiran' => 0.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agenda_rapat', function (Blueprint $table) {
            $table->dropColumn('notula');
        });

        Schema::table('absensi', function (Blueprint $table) {
            $table->dropColumn('bukti_foto');
        });

        DB::table('status_absensi')->where('nama_status', 'Sakit')->delete();
    }
};
