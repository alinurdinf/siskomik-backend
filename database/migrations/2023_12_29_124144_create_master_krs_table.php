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
        Schema::create('master_krs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_krs');
            $table->string('prodi');
            $table->string('semester');
            $table->string('total_sks');
            $table->string('tahun_akademik');
            $table->string('status');
            $table->string('keterangan')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_krs');
    }
};
