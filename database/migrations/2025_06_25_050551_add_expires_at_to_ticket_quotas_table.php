<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpiresAtToTicketQuotasTable extends Migration
{
    public function up()
    {
        Schema::table('ticket_quotas', function (Blueprint $table) {
            $table->date('expires_at')->nullable()->after('event_id');
        });
    }

    public function down()
    {
        Schema::table('ticket_quotas', function (Blueprint $table) {
            $table->dropColumn('expires_at');
        });
    }
}