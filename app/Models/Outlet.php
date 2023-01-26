<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Outlet extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'outlets';
    protected $fillable = [
        'outlet_name',
        'address',
        'mobile',
        'details',
        'is_active',
    ];
}
