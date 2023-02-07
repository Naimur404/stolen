<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseReturnDetails extends Model
{
    use HasFactory;

    protected $table = 'warehouse_return_details';
    protected $fillable = [
        'warehouse_return_id',
        'medicine_id',
        'medicine_name',
        
        'expiry_date',
        'quantity',
        'has_received'
    ];
}
