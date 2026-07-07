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
        Schema::create('json_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // label/nama sumber
            $table->string('url');             // URL JSON dari server global
            $table->string('type');            // 'rumah_sakit' | 'alat' | 'penandatangan'
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('json_sources');
    }
};
