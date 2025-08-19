<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Message;

class Report extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = ['user_id', 'message_id'];

    protected $primaryKey = 'report_id';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function message()
    {
        return $this->hasOne(Message::class, 'message_id', 'message_id');
    }
}
