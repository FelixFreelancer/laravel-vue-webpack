<?php
namespace App\Transformers\Admin;

class ContactTransformer
{
    public static function transform($data)
    {
        return [
          'id' => $data['id'],
          'user_id' => $data['user_id'],
          'username' => $data['username'],
          'name' => $data['name'],
          'email' => $data['email'],
          'status' => $data['status'] == 1 ? 'Closed' : 'Open',
          'subject' => $data['subject'],
          'message' => $data['message'],
          'date' => strtotime($data['created_at']),
        ];
    }
}
