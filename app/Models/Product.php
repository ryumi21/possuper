<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'Oid';
    public $timestamps = false;

    protected $fillable = [
        'Code',
        'Name',
        'Type',
        'Price',
        'BuyPrice',
        'SellPrice',
        'IsStock',
        'IsActive',
    ];

    /**
     * Relationship to the ProductMaterial model
     */
    public function productMaterials()
    {
        return $this->hasMany(ProductMaterial::class, 'Product_Id', 'Oid');
    }
}

