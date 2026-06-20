<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMaterial extends Model
{
    protected $table = 'product_material';
    protected $primaryKey = 'Oid';
    
    // Enable timestamps since the table has created_at and updated_at
    public $timestamps = true;

    protected $fillable = [
        'Product_Id',
        'Name',
        'Create_Cost',
        'ItemUnit',
    ];

    /**
     * Relationship back to the Food model
     */
    public function food()
    {
        return $this->belongsTo(Food::class, 'Product_Id', 'Oid');
    }

    /**
     * Relationship to the ItemUnit model
     */
    public function unit()
    {
        return $this->belongsTo(ItemUnit::class, 'ItemUnit', 'Oid');
    }

}
