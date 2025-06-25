<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('ticket_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // PTxxxxxx
            $table->foreignId('ticket_quota_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('member_count')->default(0); // Mitglieder-Tickets
            $table->unsignedInteger('non_member_count')->default(0); // Nicht-Mitglieder-Tickets
            $table->unsignedInteger('travel_count')->nullable(); // Anreise (optional)
            $table->decimal('total_price', 8, 2);
            $table->boolean('confirmed')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_orders');
    }
}