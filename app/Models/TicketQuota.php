<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketQuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'total_tickets',
        'price_member',
        'price_non_member',
        'fanclub_travel',
        'fanclub_travel_price',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}