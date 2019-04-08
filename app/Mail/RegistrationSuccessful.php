<?php

namespace App\Mail;

use App\Models\Country;
use App\Models\Option;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationSuccessful extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $option = Option::where('type', '=', 1)->pluck('value', 'key');
        $country = Country::where('id', '=', $this->user->cd_country)->first();
        return $this->markdown('mails.registration_successful')
            ->subject('Welcome to Globalparcelforward, '.ucwords(strtolower($this->user->first_name.' '.$this->user->last_name)))
            ->with([
                'option'  => $option,
                'country' => $country
            ]);
    }
}
