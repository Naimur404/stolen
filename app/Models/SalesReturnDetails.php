<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturnDetails extends Model
{
    use HasFactory;

    protected $table = 'sales_return_details';

    protected $fillable = [
        'sales_return_id',
        'medicine_id',
        'medicine_name',
        'expiry_date',
        'sold_qty',
        'return_qty',
        'rate',
        'total_price',
    ];
}
