<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Country;

class News extends Model
{
    use HasFactory;

    protected $primaryKey = 'news_id';
    protected $table = 'news';
    public $timestamps = true;
    protected $fillable = ['title','content','user_id','country_id'];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'country_id');
    }
}
