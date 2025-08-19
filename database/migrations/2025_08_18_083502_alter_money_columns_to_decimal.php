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
        Schema::table('events', function (Blueprint $t) {
            $t->decimal('price', 12, 2)->change();
        });
        Schema::table('payments', function (Blueprint $t) {
            $t->decimal('amount', 12, 2)->change();
            if (!Schema::hasColumn('payments', 'external_order_id')) {
                $t->string('external_order_id')->nullable()->after('id')->index();
            }
            if (!Schema::hasColumn('payments', 'redirect_url')) {
                $t->string('redirect_url')->nullable()->after('external_order_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', fn (Blueprint $t) => $t->double('price')->change());
        Schema::table('payments', function (Blueprint $t) {
            $t->double('amount')->change();
            $t->dropColumn(['external_order_id','redirect_url']);
        });
    }
};
