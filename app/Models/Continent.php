<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Country;

class Continent extends Model
{
    use HasFactory;


    public function getCountries()
    {
        return $this->hasMany(Country::class);
    }
}
