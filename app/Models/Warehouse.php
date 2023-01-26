<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'warehouses';
    protected $fillable = [
        'warehouse_name',
        'address',
        'mobile',
        'details',
        'is_active',
    ];
}
