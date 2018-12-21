<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttendeeMessage extends Model
{
    protected $guarded = [];

    public function concert()
    {
        return $this->belongsTo(Concert::class);
    }

    public function orders()
    {
        return $this->concert->orders();
    }

    // If you were trying to load entire order records from the database, like elquent models
    // Solution: a feature of laravel database query system called chunk, which let you basically fetch all results
    // from the database and chunks, and only ever have those chunks in memory at the time, so you can really lower your memory requirements.
    public function withChunkedRecipients($chunkSize, $callback)
    {
        $this->orders()->chunk($chunkSize, function ($orders) use ($callback) {
            $callback($orders->pluck('email'));
        });
    }
}
