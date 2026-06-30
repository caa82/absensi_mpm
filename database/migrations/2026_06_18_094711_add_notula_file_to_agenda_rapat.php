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
        Schema::table('agenda_rapat', function (Blueprint $table) {
            $table->string('notula_file')->nullable()->after('notula');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agenda_rapat', function (Blueprint $table) {
            $table->dropColumn('notula_file');
        });
    }
};
