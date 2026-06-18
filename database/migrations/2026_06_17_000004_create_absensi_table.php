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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id('id_absensi');
            $table->foreignId('id_agenda')->constrained('agenda_rapat', 'id_agenda')->onDelete('cascade');
            $table->foreignId('id_anggota')->constrained('anggota', 'id_anggota')->onDelete('cascade');
            $table->foreignId('id_status')->constrained('status_absensi', 'id_status')->onDelete('cascade');
            $table->dateTime('waktu_absen');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Unique constraint: one attendance per member per agenda
            $table->unique(['id_agenda', 'id_anggota']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
