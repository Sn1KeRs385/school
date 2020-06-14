<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\ApiModels\EmailMeterHistory;

class MetersData extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;
    protected $address;
   
    public function __construct(EmailMeterHistory $data, string $address)
    {
        $this->data = $data;
        $this->address = $address;
    }


    public function build()
    {
        return $this->view('mail.meter.notifier')
            ->subject('Показания счетчиков')
            ->with([
                'data' => $this->data,
                'address' => $this->address
            ]);
    }
}
