<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $table = 'transaction_detail';
    protected $primaryKey = 'Oid';
    
    // Enable Eloquent timestamps (created_at & updated_at exist in schema)
    public $timestamps = true;

    protected $fillable = [
        'Transaction_Id',
        'Product_Id',
        'Note',
        'Value',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'Transaction_Id', 'Oid');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'Product_Id', 'Oid');
    }

    public function food()
    {
        return $this->belongsTo(Food::class, 'Product_Id', 'Oid');
    }
}
