<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuotationReponseFromAdmin extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $quotation,$loggedInUser,$user;

    public function __construct($quotation,$loggedInUser,$user)
    {
        $this->quotation = $quotation;
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
            ->subject('Your personal shopper quote is ready!')
            ->markdown('mails.quotation_response', [
                'quotation_number' => $this->quotation->quotation_number,
                'user'             => $this->user
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
            'quotation_id' => $this->quotation->id,
            'user_id'        => $this->loggedInUser->id,
        ];
    }
}
