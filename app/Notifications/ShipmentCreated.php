<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShipmentCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $shipment, $user,  $loggedInUser;

    public function __construct($shipment, $user, $loggedInUser)
    {
        $this->shipment = $shipment;
        $this->user = $user;
        $this->loggedInUser = $loggedInUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('We have recieved a new package in your warehouse.')
            ->markdown('mails.shipment_created', [
                'shipment' => $this->shipment,
                'user'     => $this->user
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'shipment_id' => $this->shipment->id,
            'user_id'     => $this->loggedInUser->id
        ];
    }
}
