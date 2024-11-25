<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barber extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'last_name', 'phone', 'photo'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($barber) {
            if (Barber::where('phone', $barber->phone)->orWhere(function ($query) use ($barber) {
                $query->where('name', $barber->name)
                    ->where('last_name', $barber->last_name);
            })->exists()) {
                throw new \Exception("A Barber with such data already exists.");
            }
        });
    }
}
