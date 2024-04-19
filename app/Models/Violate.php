<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Violate extends Model
{
    use HasFactory;
    protected $fillable = [
        'accuser',
        'feel_id',
        'content',
    ];

    /**
     * Get the user associated with the Violate
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function feel()
    {
        return $this->hasOne(Post::class, 'id', 'feel_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'accuser', 'id');
    }
}
