<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicinePurchaseDetails extends Model
{
    use HasFactory;
    protected $table = 'medicine_purchase_details';
    protected $fillable = [
        'warehouse_id',
        'medicine_purchase_id',
        'medicine_id',
        'medicine_name',
        'product_type',
        'rack_no',
        'expiry_date',
        'quantity',
        'manufacturer_price',
        'box_mrp',
        'rate',
        'total_price',
        'vat',
        'total_discount',
        'total_amount',
    ];
}
