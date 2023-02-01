<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletCheckIn extends Model
{
    use HasFactory;
    protected $table = 'outlet_check_ins';
    protected $fillable = [
        'outlet_id',
        'medicine_distribute_id',
        'medicine_id',
        'expiry_date',
        'quantity',
        'checked_by',
        'remarks',
    ];
}
