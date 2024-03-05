<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'content',
        'type',
        'status',
        'link',
        'member_name',
        'member_image',
    ];
}
