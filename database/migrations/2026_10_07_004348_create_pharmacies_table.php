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
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_order_id')->unique()->constrained()->cascadeOnDelete();

            // Data Apotek
            $table->string('nama_apotek');
            $table->string('nomor_izin_apotek')->nullable()->comment('SIA / Sertifikat Standar');
            $table->text('alamat_apotek')->nullable();
            $table->string('telp_apotek')->nullable();

            // Data Apoteker
            $table->string('nama_apoteker')->nullable();
            $table->string('nomor_sipa')->nullable();
            $table->text('alamat_apoteker')->nullable();
            $table->string('telp_apoteker')->nullable();

            // Data Pemilik
            $table->string('nama_pemilik');
            $table->string('telp_pemilik');
            $table->text('alamat_pemilik')->nullable();

            $table->string('logo_path')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacies');
    }
};
