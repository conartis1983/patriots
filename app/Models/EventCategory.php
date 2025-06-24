<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCategory extends Model
{
    protected $fillable = ['name'];

    public function types()
    {
        return $this->hasMany(EventType::class);
    }
}