<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
            $table->string('phone')->nullable()->after('last_name');
            $table->date('birthdate')->nullable()->after('phone');
            $table->boolean('is_admin')->default(false)->after('birthdate');
            // Optional: Entferne das alte 'name'-Feld, wenn du es nicht mehr brauchst
            // $table->dropColumn('name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'phone', 'birthdate', 'is_admin']);
            // Optional: Füge das alte 'name'-Feld wieder hinzu
            // $table->string('name');
        });
    }
};