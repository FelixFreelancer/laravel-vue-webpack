<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Shipment;
use App\Models\ShipmentItem;
use Yajra\DataTables\Facades\DataTables;

class ShipmentItemController extends Controller
{
    public function index(Shipment $shipment)
    {
        
        $data = [];
        $data['shipment'] = $shipment;
        return view('admin.pages.shipment-items.index', $data);
    }

    public function create(Shipment $shipment)
    {
        $data = [];
        $data['shipment'] = $shipment;
        return view('admin.pages.shipment-items.create', $data);
    }

    public function store(Shipment $shipment)
    {
        

        $this->validate(request(), ShipmentItem::validationRules());
        $item = new ShipmentItem(request()->all());
        $item->shipment_id = $shipment->id;

        if (!$item->save()) {
            session()->flash('message', 'Something went wrong.');
            session()->flash('class', 'danger');
            return back();
        }

        if (request('image')) {
            Media::whereIn('id', request('image'))->update([
                'main_id' => $item->id
            ]);
        }

        session()->flash('message', 'Item added successfully.');
        session()->flash('message', 'success');
        return redirect()->route('admin.shipment-items.index', $shipment->id);
    }

    public function edit(Shipment $shipment, ShipmentItem $shipment_item)
    {
        if ($shipment->id == $shipment_item->shipment_id) {
            $data = [];
            $data['shipment'] = $shipment;
            $shipment_item->medias = Media::where('main_id', '=', $shipment_item->id)
                ->where('type', '=', 'ShipmentItem')
                ->get();
            $data['item'] = $shipment_item;
            return view('admin.pages.shipment-items.create', $data);
        } else {
            abort('404');
        }
    }

    public function update(Shipment $shipment, ShipmentItem $shipment_item)
    {
        if ($shipment->id == $shipment_item->shipment_id) {
            $this->validate(request(), ShipmentItem::validationRules());
            if (!$shipment_item->update(request()->all())) {
                session()->flash('message', 'Something went wrong.');
                session()->flash('class', 'danger');
                return back();
            }

            if (request('image')) {
                Media::whereIn('id', request('image'))->update([
                    'main_id' => $shipment_item->id
                ]);
            }
            session()->flash('message', 'Item added successfully.');
            session()->flash('message', 'success');
            return redirect()->route('admin.shipment-items.index', $shipment->id);
        } else {
            abort('404');
        }
    }

    public function destroy(Shipment $shipment, ShipmentItem $shipment_item)
    {
        if ($shipment->id == $shipment_item->shipment_id) {
            $data = [];
            $data['status'] = $shipment_item->delete();
            return $data;
        } else {
            abort('404');
        }
    }

    public function indexDatatable(Shipment $shipment)
    {
        $data = ShipmentItem::select('*')
            ->where('shipment_id', '=', $shipment->id);

        return DataTables::of($data)
            ->addColumn('action', function ($row) use ($shipment) {
                $str = '';
                $str .= '<a href="' . url()->route('admin.shipment-items.edit', [$shipment->id, $row->id]) . '"  class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit details">
							<i class="la la-edit"></i>
						</a>
						<a href="#" data-id="' . $row->id . '" class="deleteShipmentItem m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Delete">
							<i class="la la-trash"></i>
						</a>';
                return $str;
            })
            ->make(true);
    }
}
