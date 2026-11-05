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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            // Prefix dipakai di partnerReferenceNo GDC (mis. 'APB-<uuid>') supaya
            // webhook tunggal GDC bisa didemuks per cabang -- lihat BranchQrisPayment.
            $table->string('code', 20)->unique();
            $table->string('name');
            // Shared secret, bukan password -- disimpan plaintext dengan sengaja karena
            // hub perlu mengirimkannya balik saat meneruskan notifikasi ke callback_url
            // milik cabang (cabang memverifikasi notifikasi hub dengan key yang sama).
            $table->string('api_key')->unique();
            $table->string('callback_url');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
