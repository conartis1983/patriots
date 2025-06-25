<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketQuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'expires_at',
        'total_tickets',
        'price_member',
        'price_non_member',
        'fanclub_travel',
        'fanclub_travel_price',
    ];

    public function event()
    {
        return $this->belongsTo(\App\Models\Event::class, 'event_id');
    }

    public function ticketOrders()
    {
        return $this->hasMany(TicketOrder::class);
    }
}