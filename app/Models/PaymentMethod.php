<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'payment_methods';
    protected $fillable = [
        'method_name',
    ];
    public static function getPayment($id)
    {
        if ($id != null) {
            return PaymentMethod::find($id)->method_name;
        }
        else
            return null;
    }

}
