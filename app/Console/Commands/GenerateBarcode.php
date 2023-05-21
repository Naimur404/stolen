<?php

namespace App\Console\Commands;

use App\Models\BarcodeLog;
use App\Models\OutletStock;
use Illuminate\Console\Command;

class GenerateBarcode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:generateBarcode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $stocks = OutletStock::whereNull('barcode_text')->orWhere('barcode_text', '')->get();
        foreach ($stocks as $stock){
            $stock->barcode_text = BarcodeLog::generateBarcodeText();
            $stock->save();
        }
        $this->info('Barcode generated successfully!');
        return Command::SUCCESS;
    }
}
