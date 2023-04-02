<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'color',
    ];

    public function getMyBirthdayAttribute()
    {
        return $this->my_birthday->format('d.m.Y');
    }
}
