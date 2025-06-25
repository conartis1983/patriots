<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketOrder extends Model
{
    protected $fillable = [
        'order_number',
        'ticket_quota_id',
        'user_id',
        'member_count',
        'non_member_count',
        'travel_count',
        'total_price',
        'confirmed',
    ];

    public function ticketQuota()
    {
        return $this->belongsTo(\App\Models\TicketQuota::class, 'ticket_quota_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Setze order_number automatisch beim Erstellen
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }

    protected static function generateOrderNumber()
    {
        $lastId = self::max('id') ?? 0;
        return 'PT' . str_pad($lastId + 1, 6, '0', STR_PAD_LEFT);
    }
}