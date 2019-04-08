<?php
namespace App\Transformers;

use App\Models\ShipmentItem;
use App\Models\Quotation;
use App\Models\User;

class NotificationTransformer
{
    public static function transform($data)
    {
        $date_diff = time() - strtotime(date($data['created_at']));
        $user = User::find($data['data']['user_id']);
        $name = ucwords(strtolower($user['first_name']." ".$user['last_name']));
        $rst = [
          'id' => $data['id'],
          'time_elapsed' => strtotime($data['created_at']),
          'read_at' => $data['read_at'] != "" ? date('d-m-Y H:i:s',strtotime($data['read_at'])) : NULL,
          'type' => $data['type'],
          'icon' => NULL,
        ];

        if($data['type'] == 'App\Notifications\RequestShipmentItemPhoto'){
          $shipmentItem = ShipmentItem::find($data['data']['shipment_item_id']);
          $rst += [
            'shipment_id' => $shipmentItem['shipment_id'],
            'item_id' => $shipmentItem['id'],
            'notification' => $name ." is requesting photo of item named ". $shipmentItem['item_name'] .".",
            'redirect_url' => '/app/shipments/'.$shipmentItem['shipment_id'].'/items/'.$shipmentItem['id'],
          ];
        }

        if($data['type'] == 'App\Notifications\QuotationCreated'){
          $quotation = Quotation::find($data['data']['quotation_id']);
          $rst += [
            'quotation_number' => $quotation['quotation_number'],
            'notification' => $name ." has created new quotation with ". $quotation['quotation_number'] ." quotation number.",
            'redirect_url' => '/app/quotations/'.$quotation['quotation_number'],
          ];
        }

        if($data['type'] == 'App\Notifications\ShipmentCreated'){
          $rst += [
            'notification' => "New Shipment has been added by ".$name,
            'redirect_url' => '/dashboard/#/action-box',
          ];
        }

        if($data['type'] == 'App\Notifications\QuotationReponseFromAdmin'){
          $quotation = Quotation::find($data['data']['quotation_id']);
          $rst += [
            'notification' => $name." has responded for quotation with ".$quotation['quotation_number'] ." quotation number.",
            'redirect_url' => '/dashboard/#/personal-shopper',
          ];
        }
        return $rst;
    }

}
