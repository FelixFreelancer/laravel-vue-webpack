<?php
namespace App\Transformers\Admin;

class MailTransformer
{
    public static function transform($data)
    {
        return [
          'id' => $data['id'],
          'from' => $data['from_user'],
          'to' => $data['to_user'],
          'subject' => $data['subject'],
          'mail' => $data['mail'],
          'created_at' => strtotime($data['created_at'])
        ];
    }
}
