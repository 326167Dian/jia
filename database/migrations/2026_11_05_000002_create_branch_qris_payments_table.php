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
        // Terpisah dari `qris_payments` (penjualan MySifa sendiri) dengan sengaja --
        // pembayaran yang diteruskan dari cabang (apotek) tidak boleh bersinggungan
        // dengan alur penjualan software MySifa yang sudah berjalan di tabel itu.
        Schema::create('branch_qris_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            // Reference milik cabang sendiri (dikirim balik ke cabang apa adanya).
            $table->uuid('branch_reference_no');
            // Yang benar-benar dikirim ke GDC sebagai partnerReferenceNo: "{code}-{branch_reference_no}".
            $table->string('prefixed_reference_no')->unique();
            $table->string('external_id')->nullable();
            $table->string('gdc_reference_no')->nullable();
            $table->unsignedBigInteger('amount');
            $table->text('qr_content')->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->dateTime('expires_at');
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamps();

            $table->unique(['branch_id', 'branch_reference_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_qris_payments');
    }
};
