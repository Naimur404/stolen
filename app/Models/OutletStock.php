<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletStock extends Model
{
    use HasFactory;
    protected $table = 'outlet_stocks';
    protected $fillable = [
        'outlet_id',
        'medicine_id',
        'expiry_date',
        'quantity',
        'price',
    ];
}
