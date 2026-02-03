<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
        'location',
        'price_per_month',
        'size',
        'description',
        'image_url',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
