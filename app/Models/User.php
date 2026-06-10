<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'user';
    protected $primaryKey = 'Oid';
    public $timestamps = false;

    protected $fillable = [
        'Code',
        'Name',
        'Password',
        'IsRole',
        'IsActive',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'IsRole', 'Oid');
    }

    // Override the getAuthPassword method to use the custom 'Password' column
    public function getAuthPassword()
    {
        return $this->Password;
    }
}
