<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketQuotasTable extends Migration
{
    public function up()
    {
        Schema::create('ticket_quotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->integer('total_tickets');
            $table->decimal('price_member', 8, 2);
            $table->decimal('price_non_member', 8, 2);
            $table->boolean('fanclub_travel')->default(false);
            $table->decimal('fanclub_travel_price', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_quotas');
    }
}