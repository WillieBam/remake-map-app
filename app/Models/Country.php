<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Continent;
class Country extends Model
{
    use HasFactory;
    protected $primaryKey = 'country_id';
    public function continent(){
         return $this->hasOne(Continent::class, 'continent_id', 'continent_id');
    }
}
