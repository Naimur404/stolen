<?php

namespace App\Console\Commands;

use App\Models\OutletInvoice;
use Illuminate\Console\Command;

class ChangeInvoiceData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:changeInvoiceData';

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
        $invoices = OutletInvoice::all();
        foreach ($invoices as $invoice) {
            $invoice->given_amount = $invoice->paid_amount;
            $invoice->paid_amount = $invoice->paid_amount > $invoice->grand_total ? round($invoice->grand_total) : $invoice->paid_amount;
            $invoice->save();
        }
        return Command::SUCCESS;
    }
}
