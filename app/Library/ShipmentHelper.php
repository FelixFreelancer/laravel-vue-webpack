<?php

use App\Models\ShipmentHistory;

function addShipmentStatus($shipment_id, $status_id, $userid, $notes = '')
{
    ShipmentHistory::create([
        'shipment_id' => $shipment_id,
        'user_id'   => $userid,
        'status_id'   => $status_id,
        'notes'       => $notes
    ]);
}

function deleteShipmentStatus($shipment_id,$status,$user_id)
{
    $history = ShipmentHistory::where('shipment_id',$shipment_id)->where('status_id',$status)->first();
    if($history){
      $history->deleted_by = $user_id;
      $history->save();
      $history->delete();
    }
}
