<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		$user = '';
		if(isset($this->data['userObj'])){
			$user = $this->data['userObj'];
		}else{
			$user = User::find($this->data['user_id']);
			$user['name'] = $user['first_name']." ".$user['last_name'];
		}
        return $this->markdown('mails.mail')
			->from(env('ADMIN_EMAIL', 'hello@example.com'),env('MAIL_FROM_NAME', 'Example'))
            ->subject($this->data['subject'])
            ->with([
                'content'  => $this->data['mail'],
                'subject'  => $this->data['subject'],
                'user' => $user
            ]);
    }
}
