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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('external_order_id')->nullable()->unique()->after('id');
            $table->string('redirect_url')->nullable()->after('payment_status');
            // (opsional) ganti enum method biar generik / sesuai midtrans
            $table->string('payment_method')->default('midtrans')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['external_order_id','redirect_url']);
            // tidak rollback change() untuk payment_method karena tergantung driver
        });
    }
};
