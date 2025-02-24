<?php

namespace Database\Seeders;

use App\Models\OutletStock;
use App\Models\WarehouseStock;
use Illuminate\Database\Seeder;

class BarcodeAdd extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch all warehouse stock data needed
        $warehouseStocks = WarehouseStock::select('id', 'medicine_id', 'size', 'barcode_text')
            ->get()
            ->groupBy(fn($item) => $item->medicine_id . '-' . $item->size);

        // Process outlet stocks in chunks for memory efficiency
        OutletStock::whereNull('barcode_text')->chunk(1000, function ($outletStocks) use ($warehouseStocks) {
            foreach ($outletStocks as $outletStock) {
                $key = $outletStock->medicine_id . '-' . $outletStock->size;
                $warehouseStock = $warehouseStocks->get($key)?->first();

                if ($warehouseStock) {
                    $outletStock->update([
                        'barcode_text' => $warehouseStock->barcode_text,
                        'warehouse_stock_id' => $warehouseStock->id,
                    ]);
                }
            }
        });
    }
}
