<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';
    protected $primaryKey = 'Oid';
    public $timestamps = false;

    protected $fillable = [
        'Code',
        'Name',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'IsRole', 'Oid');
    }
}
