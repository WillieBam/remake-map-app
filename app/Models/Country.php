<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $primaryKey = 'country_id';

    function messages(){
        return $this->hasMany(Message::class, 'country_id', 'country_id');
    }

    function news(){
        return $this-> hasMany(News::class, 'country_id','country_id');
    }

    function continent(){
        return $this->belongsTo(Continent::class, 'continent_id', 'continent_id');
    }
}
