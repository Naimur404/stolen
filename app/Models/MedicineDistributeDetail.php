<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineDistributeDetail extends Model
{
    use HasFactory;
    protected $table = 'medicine_distribute_details';
    protected $fillable = [
        'medicine_distribute_id',
        'medicine_id',
        'medicine_name',
        'rack_no',
        'expiry_date',
        'quantity',
        'rate',
    ];
}
