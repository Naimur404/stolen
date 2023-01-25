<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierHasManufacturer extends Model
{
    use HasFactory;
    protected $table = 'supplier_has_manufacturers';
    protected $fillable = [
        'supplier_id',
        'manufacturer_id',
    ];

    public static function getManufacturer($id)
    {
        $manufacturers_id = SupplierHasManufacturer::where('supplier_id', '=', $id)->pluck('manufacturer_id');
        return Manufacturer::whereIn('id', $manufacturers_id)->select('id', 'manufacturer_name as name')->get();
    }
}
