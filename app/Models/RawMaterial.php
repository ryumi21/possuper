<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    protected $table = 'raw_material';
    protected $primaryKey = 'Oid';
    
    // Enable timestamps since the table has created_at and updated_at
    public $timestamps = true;

    protected $fillable = [
        'Name',
        'Category',
        'unit',
        'current_stock',
        'minimum_stock',
        'purchase_price',
        'status',
    ];

    /**
     * Relationship to the ItemUnit model
     */
    public function itemUnit()
    {
        return $this->belongsTo(ItemUnit::class, 'unit', 'Oid');
    }
}
