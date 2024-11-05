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
    protected $guarded = ['id'];
    protected $hidden = [
        'pivot',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $fillable = [
        'name',
        'mobile',
        'address',
        'outlet_id',
        'points',
        'due_balance',
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

