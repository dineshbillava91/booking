<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['id', 'content', 'rating'];

    public function getIncrementing()
    {
        return false;
    }

    //primary key is not bigint, its uuid so type will be string nt int. so make changes in gettype function (override)
    public function getKeyType()
    {
        return 'string';
    }

    public function bookable()
    {
        return $this->belongsTo(Bookable::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
