<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    protected $fillable = ['name', 'event_category_id'];

    public function category()
    {
        return $this->belongsTo(EventCategory::class);
    }
}