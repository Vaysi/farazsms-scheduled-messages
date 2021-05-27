<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function numbers()
    {
        return $this->hasMany(Number::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

}
