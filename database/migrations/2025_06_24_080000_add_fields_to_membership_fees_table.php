<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membership_fees', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            $table->year('year')->after('user_id');
            $table->boolean('paid')->default(false)->after('year');
            $table->date('paid_at')->nullable()->after('paid');
        });
    }

    public function down(): void
    {
        Schema::table('membership_fees', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'year', 'paid', 'paid_at']);
        });
    }
};