<?php

namespace App\ViewComposers;


use Illuminate\View\View;
use App\Transformers\NotificationTransformer;
class HeaderComposer
{
    public function compose(View $view)
    {
        $data = [];
        if (auth()->check()) {
            $data['user'] = $user = auth()->user();

            $data['notifications'] = [];
            $notifications = $user->unreadNotifications()->whereIn('type',['App\Notifications\ShipmentCreated','App\Notifications\QuotationReponseFromAdmin'])->get();

            foreach($notifications as $key => $value){
               $notification = NotificationTransformer::transform($value);
               $notification['time_elapsed'] = date('d-m-Y H:i',$notification['time_elapsed']);
			   $data['notifications'][] = $notification;
            }

            $data['notification_count'] = $user->notifications()->whereIn('type',['App\Notifications\ShipmentCreated','App\Notifications\QuotationReponseFromAdmin'])->count();
            $data['unread_notification_count'] = $user->unreadNotifications()->whereIn('type',['App\Notifications\ShipmentCreated','App\Notifications\QuotationReponseFromAdmin'])->count();
        }
        $view->with($data);
    }
}
