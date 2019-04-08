<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Shipment;
use App\Models\User;
use App\Notifications\ShipmentCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Yajra\DataTables\Facades\DataTables;

class ShipmentController extends Controller
{
    public function index()
    {
        return view('admin.pages.shipments.index');
    }

    public function create()
    {
        $data = [];
        $data['users'] = User::select(DB::raw('concat(users.first_name," ",users.last_name," ",users.customer_code) as name'), 'id')
            ->where('role_id', '=', 3)
            ->pluck('name', 'id');
        return view('admin.pages.shipments.create', $data);
    }

    public function store()
    {
        $this->validate(request(), Shipment::validationRules());
        $inputs = request()->all();
        $inputs['received_on'] = date_time_database($inputs['received_on']);
        $inputs['status'] = 1;
        $shipment = new Shipment($inputs);

        if (!$shipment->save()) {
            session()->flash('message', 'Something went wrong.');
            session()->flash('class', 'danger');
            return back();
        }

        if (request('image')) {
            Media::whereIn('id', request('image'))->update([
                'main_id' => $shipment->id
            ]);
        }
        $user = User::find(request('user_id'));
        addShipmentStatus($shipment->id, 1);
        Notification::send($user, new ShipmentCreated($shipment, $user));
        session()->flash('message', 'Shipment added successfully.');
        session()->flash('class', 'success');
        return redirect()->route('admin.shipments.index');
    }

    public function edit(Shipment $shipment)
    {
        $data = [];
        $data['users'] = User::select(DB::raw('concat(users.first_name," ",users.last_name," ",users.customer_code) as name'), 'id')
            ->where('id', '=', $shipment->user_id)
            ->where('role_id', '=', 3)
            ->pluck('name', 'id');
        $shipment->received_on = datepicker_date_time($shipment->received_on);
        $shipment->medias = Media::where('main_id', '=', $shipment->id)
            ->where('type', '=', 'Shipment')
            ->get();
        $data['shipment'] = $shipment;
        return view('admin.pages.shipments.create', $data);
    }

    public function update(Shipment $shipment)
    {
        $this->validate(request(), Shipment::validationRules());
        $inputs = request()->all();
        $inputs['received_on'] = date_time_database($inputs['received_on']);
        if (!$shipment->update($inputs)) {
            session()->flash('message', 'Something went wrong.');
            session()->flash('class', 'danger');
            return back();
        }

        if (request('image')) {
            Media::whereIn('id', request('image'))->update([
                'main_id' => $shipment->id
            ]);
        }
        session()->flash('message', 'Shipment updated successfully.');
        session()->flash('class', 'success');
        return redirect()->route('admin.shipments.index');
    }

    public function destroy(Shipment $shipment)
    {
        $data = [];
        $data['status'] = $shipment->delete();
        return $data;
    }

    public function indexDatatable()
    {
        $data = Shipment::select('shipments.*', DB::raw('concat(users.first_name," ",users.last_name) as user_name'))
            ->leftJoin('users', 'users.id', '=', 'shipments.user_id');
        if(request('status')){
          $data->where('status',request('status'));
        }
        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                $str = '';
                $str .= '<a href="' . url()->route('admin.shipments.edit', $row->id) . '"  data-id="' . $row->id . '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit details">
							<i class="la la-edit"></i>
						</a>
						<a href="' . url()->route('admin.shipment-items.index', $row->id) . '"  data-id="' . $row->id . '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Shipment Items details">
							<i class="la la-list-ul"></i>
						</a>
						<a href="#" data-id="' . $row->id . '" class="deleteShipment m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete">
							<i class="la la-trash"></i>
						</a>';
            if(request('status') == 4){
              $str .= '<a href="#" data-id="' . $row->id . '" class="confirmPayment m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Confirm Payment">
  							<i class="la la-thumbs-up"></i>
  						</a>';
            }
            if(request('status') == 5){
              $str .= '<a href="#" data-id="' . $row->id . '" class="readyForShip m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Ready For Shipment">
  							<i class="fa fa-shopping-cart"></i>
  						</a>';
            }
            if(request('status') == 6){
              $str .= '<a href="#" data-id="' . $row->id . '" class="delivered m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Ready For Shipment">
  							<i class="la la-truck"></i>
  						</a>';
            }
                return $str;
            })
            ->make(true);
    }

    public function paymentConfirmaion()
    {
        return view('admin.pages.shipments.payment-confirmation');
    }

    public function confirmPayment($id)
    {
      $data['status'] = 1;
        $shipment = Shipment::find($id);
        $shipment->status = 4;
        $shipment->save();
        return $data;
    }

    public function readyForShipment()
    {
        return view('admin.pages.shipments.ready_for_shipment');
    }

    public function shipped()
    {
        $data['status'] = 1;
        $shipment = Shipment::find(request('id'));
        $shipment->shipping_out_tracking = request('tracking_no');
        $shipment->shipping_out_at = date('Y-m-d H:i:s');
        $shipment->status = 5;
        $shipment->save();
        return $data;
    }

    public function readyForDelivered()
    {
        return view('admin.pages.shipments.delivered');
    }

    public function delivered()
    {
        $data['status'] = 1;
        $shipment = Shipment::find(request('id'));
        // $shipment->delivered_at = date('Y-m-d',strtotime(request('delivered_at')));
        $shipment->delivered_at = date('Y-m-d H:i:s');
        $shipment->status = 6;
        $shipment->save();
        return $data;
    }
}
