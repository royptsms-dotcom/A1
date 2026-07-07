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
        Schema::create('sertifikats', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_sertifikat');
            $table->string('nama_pt');           // PT. Sarana Megamedilab / PT. Sarana Maju
            $table->string('nama_rs');
            $table->string('nama_alat');
            $table->string('nomor_sn');
            $table->date('tanggal_kalibrasi');
            $table->date('valid_until');
            $table->string('penandatangan1_nama')->nullable();
            $table->string('penandatangan1_jabatan')->nullable();
            $table->string('penandatangan2_nama')->nullable();
            $table->string('penandatangan2_jabatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikats');
    }
};
