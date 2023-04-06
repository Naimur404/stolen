<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $summary_date;
    public $warehouseSummaries;
    public $outletSummaries;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($summary_date, $warehouseSummaries, $outletSummaries)
    {
        $this->summary_date = $summary_date;
        $this->warehouseSummaries = $warehouseSummaries;
        $this->outletSummaries = $outletSummaries;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notification')
            ->subject('Admin Notification '.$this->summary_date)
            ->with([
                'summary_date' => $this->summary_date,
                'warehouseSummaries' => $this->warehouseSummaries,
                'outletSummaries' => $this->outletSummaries
            ]);
    }
}
