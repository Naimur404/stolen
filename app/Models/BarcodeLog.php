<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BarcodeLog extends Model
{
    use HasFactory;

    protected $table = 'barcode_logs';
    protected $fillable = ['barcode_text'];

    /**
     * @return string
     * @throws \Exception
     */
    public static function generateBarcodeText(): string
    {
        $year = Carbon::now()->format('Y'); // get the current year
        $barcodeText = $year . random_int(10000000, 99999999); // concatenate the year and a random string of length 8

        // Check if the generated barcode text already exists in the database
        while (BarcodeLog::where('barcode_text', $barcodeText)->exists()) {
            $barcodeText = $year . random_int(10000000, 99999999);; // regenerate the barcode text
        }

        // Store the barcode text in the BarcodeLog model
        $barcodeLog = new BarcodeLog;
        $barcodeLog->barcode_text = $barcodeText;
        $barcodeLog->save();

        // Return the barcode text
        return $barcodeText;
    }

}
