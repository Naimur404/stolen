<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'suppliers';
    protected $fillable = [
        'supplier_name',
        'address',
        'mobile',
        'details',
        'is_active',
    ];
    public static function getSupplierName($id)
    {
        if ($id != null) {
            return Supplier::find($id)->supplier_name;
        }
        else
            return null;
    }
}
