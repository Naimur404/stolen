<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'customers';
    protected $fillable = [
        'name',
        'mobile',
        'address',
        'outlet_id',
        'points',
        'is_active',
    ];

    public static function getCustomer($id)
    {
        if ($id != null) {
            return Customer::find($id)->mobile ?? '';
        }
        else
            return null;
    }
}

