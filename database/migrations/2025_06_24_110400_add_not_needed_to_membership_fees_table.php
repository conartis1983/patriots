<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotNeededToMembershipFeesTable extends Migration
{
    public function up()
    {
        Schema::table('membership_fees', function (Blueprint $table) {
            $table->boolean('not_needed')->default(false)->after('paid');
        });
    }

    public function down()
    {
        Schema::table('membership_fees', function (Blueprint $table) {
            $table->dropColumn('not_needed');
        });
    }
}