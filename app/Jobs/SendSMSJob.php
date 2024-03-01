<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;

class SendSMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $number;
    protected $text;

    public function __construct($number, $text)
    {
        $this->number = $number;
        $this->text = $text;
    }

    public function handle()
    {
        $url = "http://services.smsnet24.com/sendSms";

        $client = new Client();


            $payload = [
                'sms_receiver' => $this->number,
                'sms_text' => $this->text,
                'campaignType' => 'T',
                'user_password' => 'stolen.com.bd2@',
                'user_id' => 'farsemac@gmail.com'
            ];
             $client->post($url, [
                'form_params' => $payload
            ]);


    }
}
