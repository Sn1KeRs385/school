<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderReformaGkh extends Mailable
{
    use Queueable, SerializesModels;

    protected $order;
    protected $created_at;
    protected $image_paths;
    protected $user_data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order, $image_paths, $user_data)
    {
        $this->order = $order;
        $this->created_at = Carbon::createFromTimestamp($this->order->created_at)
            ->setTimezone('Europe/Moscow')
            ->toDateTimeString();
        $this->image_paths = $image_paths;
        $this->user_data = $user_data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order = $this->subject('Заявка в УК')
            ->view('mail.order.notifier', [
                'order' => $this->order,
                'created_at' => $this->created_at,
                'user_data'=> $this->user_data
            ]);

        foreach($this->image_paths as $image_path){
            $order->attach(storage_path($image_path), [
                'mime'=>'image/png',
                'as' => uniqid().'.png'
            ]);
        }
    }
}
