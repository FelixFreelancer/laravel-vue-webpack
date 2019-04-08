<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Transformers\NotificationTransformer;
use App\Transformers\Admin\ShipmentTransformer;
use App\Transformers\Admin\ShipmentItemTransformer;
use App\Transformers\Admin\QuotationTransformer;
use App\Transformers\Admin\UserTransformer;
use App\Models\User;
use App\Models\Shipment;
use App\Models\Quotation;
use App\Models\ShipmentItem;
use App\Models\Payment;
use App\Models\PhotoRequest;
use App\Library\Api;

class HomeController extends Controller
{

    public function getCounter()
    {
      $data['data'] = [
        'orders' => Shipment::getCounter(request()->all()),
        'users' => User::getCounter(request()->all()),
        'revenue' => config('site.default_currency').Payment::getRevenue(request()->all()),
        'request_for_quote' => Quotation::getCounter(request()->all()),
        'request_for_photo' => PhotoRequest::getCounter(request()->all()),
      ];
      return Api::ApiResponse($data);
    }

    public function globalSearch()
    {
      $userObj = [];
      $shipmentObj = [];
      $quotationObj = [];
      $shipmentItemObj = [];
	  if(request('search')){
		$loggedInUser = Api::getAuthenticatedUser();
		$name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
		addLog($loggedInUser['user']['id'],'Search','<b>'.$name.'</b> has searched for <b>'.request('search').'</b>.');
	  }
      $user = User::globalSearch(request()->all());

      foreach($user as $key => $value){
        $userObj[] = UserTransformer::transform($value);
      }

      $shipment = Shipment::globalSearch(request()->all());
      foreach($shipment as $key => $value){
        $shipmentObj[] = ShipmentTransformer::transform($value);
      }

      $shipmentItem = ShipmentItem::globalSearch(request()->all());
      foreach($shipmentItem as $key => $value){
        $shipmentItemObj[] = ShipmentItemTransformer::transform($value);
      }

      $quotation = Quotation::globalSearch(request()->all());
      foreach($quotation as $key => $value){
        $quotationObj[] = QuotationTransformer::transform($value);
      }

      $data['data'] = [
        'user' => $userObj,
        'shipment' => $shipmentObj,
        'quotation' => $quotationObj,
        'shipment_item' => $shipmentItemObj,
      ];
      return Api::ApiResponse($data);
    }

    public function getNotification()
    {
      $loggedInUser = Api::getAuthenticatedUser();
      $user = $loggedInUser['user'];

      $notifications = $user->unreadNotifications()->whereIn('type',['App\Notifications\QuotationCreated','App\Notifications\RequestShipmentItemPhoto'])->get();
      $data['data']['notifications']=[];
      foreach($notifications as $key => $value){
        $data['data']['notifications'][] = NotificationTransformer::transform($value);
      }

      $data['data']['notification_count'] = $user->notifications()->whereIn('type',['App\Notifications\QuotationCreated','App\Notifications\RequestShipmentItemPhoto'])->count();
      $data['data']['unread_notification_count'] = $user->unreadNotifications()->whereIn('type',['App\Notifications\QuotationCreated','App\Notifications\RequestShipmentItemPhoto'])->count();
      return Api::ApiResponse($data);
    }

}
