<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Continent;

class Country extends Model
{
    protected $primaryKey = 'country_id';
    use HasFactory;

    function messages(){
        return $this->hasMany(Message::class, 'country_id', 'country_id');
    }

    function news(){
        return $this-> hasMany(News::class, 'country_id','country_id');
    }

    function continent(){
        return $this->belongsTo(Continent::class, 'continent_id', 'continent_id');
    }

    // public function Continent()
    // {
    //     return $this->belongsTo(Continent::class, 'continent_id', 'continent_id');
    // }
    // public function News()
    // {
    //     return $this->hasMany(News::class, 'country_id', 'country_id');
    // }

}
