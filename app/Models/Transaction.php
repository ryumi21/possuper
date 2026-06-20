<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transaction';
    protected $primaryKey = 'Oid';
    
    // Enable Eloquent timestamps (created_at & updated_at exist in schema)
    public $timestamps = true;

    protected $fillable = [
        'Table_No',
        'Status',
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'Transaction_Id', 'Oid');
    }
}
