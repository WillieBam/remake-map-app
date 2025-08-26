<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    public $timestamps = true;
    //country_id and user_id not sure..
    protected $fillable = ['title','content','user_id','country_id'];

    public function getUser()
    {
        return $this->hasOne(User::class);
    }

    public function getCountry()
    {
        return $this->hasOne(Country::class);
    }
}
