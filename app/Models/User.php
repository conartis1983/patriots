<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'birthdate',
        'email',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthdate' => 'date',
        'is_admin' => 'boolean',
        'password' => 'hashed',
    ];

    public function membershipFees()
    {
        return $this->hasMany(\App\Models\MembershipFee::class);
    }

    public function hasPaidFeeForCurrentYear()
    {
        $year = now()->year;
        return $this->membershipFees()
            ->where('year', $year)
            ->where('paid', true)
            ->exists();
    }
}