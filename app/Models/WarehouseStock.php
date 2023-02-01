<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseStock extends Model
{
    use HasFactory;
    protected $table = 'warehouse_stocks';
    protected $fillable = [
        'warehouse_id',
        'medicine_id',
        'expiry_date',
        'quantity',
        'price',
    ];
}
