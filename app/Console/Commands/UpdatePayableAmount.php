<?php

namespace App\Console\Commands;

use App\Models\OutletInvoice;
use Illuminate\Console\Command;

class UpdatePayableAmount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:updatePayableAmount';

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
        $outletInvoices = OutletInvoice::all();
        foreach ($outletInvoices as $invoice){
            $invoice->payable_amount = $invoice->grand_total;
            $invoice->save();
        }
        $this->info("All outlet invoice payable amount updated");
        return Command::SUCCESS;
    }
}
