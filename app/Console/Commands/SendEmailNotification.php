<?php

namespace App\Console\Commands;

use App\Helpers\SummaryHelper;
use App\Mail\AdminNotificationEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendEmailNotification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To send daily summary email notification to admin';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $summary_date = Carbon::today()->subDay()->format('Y-m-d');
        $summaryHelper = new SummaryHelper();
        $warehouseSummaries = $summaryHelper->getWarehouseSummary($summary_date);
        $outletSummaries = $summaryHelper->getOutletSummary($summary_date);

        Mail::to(ENV('TO_MAIL', ''))->send(new AdminNotificationEmail($summary_date, $warehouseSummaries, $outletSummaries));

        $this->info('Email sent successfully.');

        return Command::SUCCESS;
    }
}
