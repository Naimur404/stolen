<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Opcodes\LogViewer\Log;

class WarehouseStock extends Model
{
    use HasFactory;
    protected $table = 'warehouse_stocks';
    protected $fillable = [
        'warehouse_id',
        'medicine_id',
        'barcode_text',
        'expiry_date',
        'quantity',
        'purchase_price',
        'price',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($warehouseStock){
            if (!$warehouseStock->barcode_text) {
                $barcode = BarcodeLog::generateBarcodeText();
                // set the barcode text to the generated value
                $warehouseStock->barcode_text = $barcode;
            }
        });
    }

    public function medicine(){
        return $this->belongsTo(Medicine::class,  'medicine_id','id');
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
}
