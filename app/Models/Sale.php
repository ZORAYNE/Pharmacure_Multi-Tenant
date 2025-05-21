<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $connection = 'tenant';

    protected $fillable = [
        'total_price',
        'product_id', // Added to allow mass assignment for temporary fix
        'quantity_sold',
        'total_price',
    ];

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            SaleItem::class,
            'sale_id', // Foreign key on SaleItem table...
            'id', // Foreign key on Product table...
            'id', // Local key on Sale table...
            'product_id' // Local key on SaleItem table...
        );
    }
}
