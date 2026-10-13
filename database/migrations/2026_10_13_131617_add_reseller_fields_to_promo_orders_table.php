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
        Schema::table('promo_orders', function (Blueprint $table) {
            $table->foreignId('reseller_id')->nullable()->after('voucher_code')->constrained()->nullOnDelete();
            $table->unsignedBigInteger('commission_amount')->nullable()->after('reseller_id');
            $table->string('commission_proof_path')->nullable()->after('commission_amount');
            $table->timestamp('commission_paid_at')->nullable()->after('commission_proof_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promo_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reseller_id');
            $table->dropColumn(['commission_amount', 'commission_proof_path', 'commission_paid_at']);
        });
    }
};
