<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
        'desc',
        'created_user',
        'status',
    ];
    /**
     * The roles that belong to the Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function user()
    {
        return $this->belongsToMany(User::class, 'created_user', 'id');
    }

    public function createdUser()
    {
        return $this->belongsTo(User::class, 'created_user');
    }
}
