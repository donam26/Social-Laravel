<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'created_user',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function latestMessage()
    {
        return $this->hasOne(Message::class)
            ->latest('created_at');
    }

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}
