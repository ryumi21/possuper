<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'Oid';
    public $timestamps = false;

    protected $fillable = [
        'Category',
        'Fitur',
        'IsPos',
    ];
}
