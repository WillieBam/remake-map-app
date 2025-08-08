<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = ['content', 'user_id', 'country_id', 'views'];

   
    protected $primaryKey = 'message_id';

    /**
     // belongsTo reference: https://laravel.com/docs/12.x/eloquent-relationships#one-to-many-inverse
     // 
    */
    
    // one message belongs to one user
    public function getUser(){
        return $this->belongsTo(User::class, 'user_id', 'user_id');
        
    }

    // one message belongs to one country
    public function getCountry(){
        // Country class coming soooon
        //return $this ->belongsTo(Country::class, foreignKey:'country_id', ownerKey:'country_id');

    }

    // A message belongs to one country
    // Uncomment this when you create the Country model
    // public function country()
    // {
    //     return $this->belongsTo(Country::class, 'country_id', 'country_id');
    // }
}
