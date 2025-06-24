<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title', 'description', 'event_category_id', 'event_type_id', 'start', 'location'
    ];

    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'event_category_id');
    }

    public function type()
    {
        return $this->belongsTo(EventType::class, 'event_type_id');
    }
}