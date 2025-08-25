<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Continent;

class Country extends Model
{
    protected $primaryKey = 'country_id';
    use HasFactory;

    public function Continent()
    {
        return $this->belongsTo(Continent::class, 'continent_id', 'continent_id');
    }
    public function News()
    {
        return $this->hasMany(News::class, 'country_id', 'country_id');
    }

}
