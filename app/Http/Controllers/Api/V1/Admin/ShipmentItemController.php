<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Shipment\Item\StoreRequest;
use App\Http\Requests\Admin\Shipment\Item\UpdateRequest;
use App\Models\Media;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use App\Models\PhotoRequest;
use DB;
use App\Library\Api;

class ShipmentItemController extends Controller
{
    public function index($id)
    {
        //return 'Hi. index';
        $data['data'] = [];
        $input = request()->all();
        $searchQuery = isset($input['query']) ? $input['query'] : [];
        $shipment = ShipmentItem::select(DB::raw('SQL_CALC_FOUND_ROWS shipment_items.id'),'shipment_items.*')
          ->where('shipment_id',  $id);

        if(isset($searchQuery['name'])){
          $shipment->where('name','Like','%'.$searchQuery['name'].'%');
        }
        if(isset($searchQuery['tracking_number'])){
          $shipment->where('tracking_number','Like','%'.$searchQuery['tracking_number'].'%');
        }

        if(isset($input['page'])){
          $start = $input['limit'] * ($input['page'] - 1);
          $end = $input['limit'];
          $shipment->skip($start)->take($end);
        }

        $orderBy = isset($input['orderBy']) ? $input['orderBy'] : 'id';
        $order = "desc";
        if(isset($input['ascending']) && $input['ascending'] == "1"){
          $order = "asc";
        }
        $shipment->orderBy($orderBy,$order);

        $loggedInUser = Api::getAuthenticatedUser();
        $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
        addLog($loggedInUser['user']['id'],'Shipment Item Index',"<b>".$name."</b> has viewed <b> Shipment Item Tab.</b>");

        $shipmentObj = $shipment->get()->toArray();
        foreach($shipmentObj as $key => $value){
          $shipmentObj[$key]['amount'] = appendCurrency($value['amount']);
        }
          $data['data'] = [
            'items' =>  $shipmentObj,
            'count' =>  DB::select(DB::raw("select FOUND_ROWS() as count"))[0]->count
          ];
        return Api::ApiResponse($data);
    }


    public function store($shipment, StoreRequest $request)
    {
        //return 'Hi.';
        $item = new ShipmentItem(request()->all());
        $item->shipment_id = $shipment;

        if (!$item->save()) {
          $data['data']['error'] = 'Something went wrong';
          $statusCode = 422;
          return Api::ApiResponse($data,$statusCode);
        }

        if ($request['image']) {
          foreach($request['image'] as $key => $value){
            $rst = fileUpload($value, 'shipment_item');
            $rst['main_id'] = $item->id ;
            $media = Media::create($rst);
          }
          PhotoRequest::where('shipment_item_id',$item->id)->update(['completed_at'=>date('Y-m-d H:i:s')]);
        }
        $data['data'] = $item->toArray();
        $shipmentObject = Shipment::find($shipment);
        $shipmentItem = ShipmentItem::select(DB::raw('sum(amount) as total'))->where('shipment_id',$shipment)->first();
        if($shipmentItem != ''){
         $shipmentObject->total =  $shipmentItem['total'] != NULL ? $shipmentItem['total'] : 0;
        }
        $shipmentObject->save();
        $shipmentObject->total = $shipmentItem['total'];
        $loggedInUser = Api::getAuthenticatedUser();
        $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
        addLog($loggedInUser['user']['id'],'Shipment Item Create','Shipment Item <b>['.$item->id."]</b> has been added by <b>".$name.".</b>");

        return Api::ApiResponse($data);
    }

    public function show($shipment,$item)
    {
        $shipmentObj = Shipment::find($shipment);
        $shipmentItemObj = ShipmentItem::find($item);

        if($shipmentObj == '' || $shipmentItemObj == '' || $shipment != $shipmentItemObj->shipment_id){
          $data['data']['error'] = ($shipmentObj == '') ? 'Shipment Not Found' : ( ($shipmentItemObj == '') ? 'Shipment Item Not Found' : 'Something went wrong' );
          $statusCode = 422;
          return Api::ApiResponse($data,$statusCode);
        }
        $rst = [];
        $medias = Media::where('main_id', '=', $shipmentItemObj->id)
            ->where('type', '=', 'ShipmentItem')
            ->get();
          $media = [];
        foreach($medias as $key => $value){
            $media[] = [
              'id' => $value->id,
              'image' => config('app.url').'/'.$value->media_path.$value->media_name,
            ];
        }
        $shipmentItemObj->medias = $media;
        $data['data'] = $shipmentItemObj->toArray();

        $loggedInUser = Api::getAuthenticatedUser();
        $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
        addLog($loggedInUser['user']['id'],'Shipment Item View','Shipment Item <b>['.$item."]</b> has been viewed by <b>".$name.".</b>");

        return Api::ApiResponse($data);
    }

    public function update($shipment,$item,UpdateRequest $request)
    {
        $shipmentObj = Shipment::find($shipment);
        $shipmentItemObj = ShipmentItem::find($item);
        if($shipmentObj == '' || $shipmentItemObj == '' || $shipment != $shipmentItemObj->shipment_id){
          $data['data']['error'] = ($shipmentObj == '') ? 'Shipment Not Found' : ( ($shipmentItemObj == '') ? 'Shipment Item Not Found' : 'Something went wrong' );
          $statusCode = 422;
          return Api::ApiResponse($data,$statusCode);
        }
        if (!$shipmentItemObj->update(request()->all())) {
          $data['data']['error'] = 'Something went wrong';
          $statusCode = 422;
          return Api::ApiResponse($data,$statusCode);
        }

        if ($request['image']) {
          foreach($request['image'] as $key => $value){
            $rst = fileUpload($value, 'shipment_item');
            $rst['main_id'] = $item;
            $media = Media::create($rst);
          }
          PhotoRequest::where('shipment_item_id',$item)->update(['completed_at'=>date('Y-m-d H:i:s')]);
        }
        $medias = Media::where('main_id', '=', $item)->where('type', '=', 'ShipmentItem')->get();
        $media = [];
        foreach($medias as $key => $value){
          $media[] = [
            'id' => $value->id,
            'image' => config('app.url').'/'.$value->media_path.$value->media_name
          ];
        }

        $shipmentItemObj->medias = $media;

        $loggedInUser = Api::getAuthenticatedUser();

        $shipmentObject = Shipment::find($shipment);
        $shipmentItem = ShipmentItem::select(DB::raw('sum(amount) as total'))->where('shipment_id',$shipment)->first();
        // if($shipmentItem != ''){
        //  $shipmentObject->total =  $shipmentItem['total'] != NULL ? $shipmentItem['total'] : 0;
        // }
        // $shipmentObject->save();

        $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
        addLog($loggedInUser['user']['id'],'Shipment Item Update','Shipment Item <b>['.$item."]</b> has been updated by <b>".$name.".</b>");

        $data['data'] = $shipmentItemObj->toArray();
        return Api::ApiResponse($data);
    }

    public function delete($item)
    {
      $data['data'] = [];
     // $shipmentObj = Shipment::find($shipment);
      $shipmentItemObj = ShipmentItem::find($item);
      if($shipmentItemObj == ''){
        $data['data']['error'] = 'Shipment Item Not Found';
        $statusCode = 422;
        return Api::ApiResponse($data,$statusCode);
      }
	  $loggedInUser = Api::getAuthenticatedUser();
	  $name = $loggedInUser['user']['first_name']." ".$loggedInUser['user']['last_name'];
	  addLog($loggedInUser['user']['id'],'Shipment Item Delete','A shipment item <b>['.$item.']</b> has been deleted by <b>'.$name.'</b>');
      $shipmentItemObj->delete();
      return Api::ApiResponse($data);
    }

}
