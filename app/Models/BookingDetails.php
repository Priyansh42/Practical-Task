<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingDetails extends Model
{
    use HasFactory;

    protected $table = 'booking_details';

    protected $fillable = [
        'customer_id',
        'booking_type',
        'booking_date',
        'booking_slot',
        'from_time',
        'to_time'
    ];
}
