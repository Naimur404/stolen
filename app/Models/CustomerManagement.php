<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerManagement extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'customer_managements';
    protected $fillable = [
        'name',
        'mobile',
        'address',
        'outlet_id',
        'points',
        'is_active',
    ];
}

