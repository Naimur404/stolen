<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseCheckIn extends Model
{
    use HasFactory;
    protected $table = 'warehouse_check_ins';
    protected $fillable = [
        'warehouse_id',
        'purchase_id',
        'medicine_id',
        'create_date',
        'size',
        'quantity',
        'checked_by',
        'remarks',
    ];
}
