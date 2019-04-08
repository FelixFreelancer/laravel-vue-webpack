<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShipmentShipped extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user, $tracking_no , $tracking_link;

    public function __construct($user, $tracking_no, $tracking_link)
    {
        $this->user = $user;
        $this->tracking_no = $tracking_no;
        $this->tracking_link = $tracking_link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.shipment_shipped')
            ->subject('Your parcel has shipped.')
            ->with([
                'user'  => $this->user,
                'tracking_no' => $this->tracking_no,
                'tracking_link' => $this->tracking_link,
            ]);
    }
}
