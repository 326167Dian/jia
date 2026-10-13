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
        Schema::create('qris_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('reference_no')->unique();
            $table->string('external_id')->unique();
            $table->string('gdc_reference_no')->nullable();

            // Data pendaftaran yang ditunda sampai pembayaran dikonfirmasi
            $table->string('pending_name', 120);
            $table->string('pending_phone', 30);
            $table->string('pending_email', 150)->nullable();
            $table->string('billing_period', 20)->default('yearly');
            $table->string('voucher_code')->nullable();
            $table->unsignedBigInteger('reseller_id')->nullable();

            $table->unsignedBigInteger('original_amount');
            $table->unsignedBigInteger('discount_amount')->default(0);
            $table->unsignedBigInteger('amount');
            $table->text('qr_content')->nullable();
            $table->string('status', 20)->default('pending');
            $table->foreignId('promo_order_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('paid_at')->nullable();
            $table->dateTime('expires_at');
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qris_payments');
    }
};
