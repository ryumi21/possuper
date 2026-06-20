<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemUnit extends Model
{
    protected $table = 'itemunit';
    protected $primaryKey = 'Oid';
    
    // Enable timestamps since the table has created_at and updated_at
    public $timestamps = true;

    protected $fillable = [
        'Code',
        'Name',
        'status',
    ];
}
