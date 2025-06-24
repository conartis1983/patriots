<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'year',
        'paid',
        'paid_at',
        'not_needed',
    ];

    protected $casts = [
        'paid' => 'boolean',
        'not_needed' => 'boolean',
        'paid_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Praktischer Scope für aktuelle Jahr-Fees
    public function scopeCurrentYear($query)
    {
        return $query->where('year', now()->year);
    }
}