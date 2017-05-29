<?php

namespace App\Http\Controllers;

use App\Checklist;
use App\Support\Core;
use App\Trip;
use App\Truck;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function json_decode;
use Response;
use SmoDav\Factory\TruckFactory;
use function view;

class ProgressController extends Controller
{

    public function loading(Request $request, $id)
    {
        $truck = TruckFactory::findOrFail($id);

        $data = $request->all();
        $data['driver_id'] = $truck->driver_id;
        $data['truck_id'] = $truck->id;
        $data['type'] = Checklist::DELIVERY_NOTE;
        $data['from_station'] = $truck->contract->route->source;
        $data['to_station'] = $truck->contract->route->destination;
        $data['fields'] = json_encode($data);
        $data['inspector_id'] = $data['user_id'];
        $data['supervisor_id'] = $data['user_id'];
        $data['for_date'] = Carbon::now();

        $data['status'] = 'Approved';

        $trip = Trip::where('truck_id', $truck->id)
            ->where('stage', Core::LOADING)
            ->where('is_complete', false)
            ->orderBy('id', 'desc')
            ->first();

        $checklist = Checklist::create($data);

        $trip->update([
            'delivery_note_id' => $checklist->id,
            'stage' => Core::ENROUTE
        ]);

        $truck->update(['location' => Core::nextStep($truck->location)]);

        $delnote = $trip->deliveryNote;
        $delnote->fields = json_decode($delnote->fields);
        $trip->deliveryNote = $delnote;

        $printout = view('printouts.deliverynote')->with('trip', $trip)->render();

        return Response::json([
            'message' => 'Successfully completed loading.',
            'shouldPrint' => true,
            'printout' => $printout
        ]);
    }

    public function enroute(Request $request, $id)
    {
        $truck = TruckFactory::findOrFail($id);

        $data = $request->all();
        $data['driver_id'] = $truck->driver_id;
        $data['truck_id'] = $truck->id;
        $data['type'] = Checklist::OFFLOADING_NOTE;
        $data['from_station'] = $truck->contract->route->source;
        $data['to_station'] = $truck->contract->route->destination;
        $data['fields'] = json_encode($data);
        $data['inspector_id'] = $data['user_id'];
        $data['supervisor_id'] = $data['user_id'];
        $data['for_date'] = Carbon::now();
        $data['status'] = 'Approved';

        $trip = Trip::where('truck_id', $truck->id)
            ->where('stage', Core::ENROUTE)
            ->where('is_complete', false)
            ->orderBy('id', 'desc')
            ->first();

        $checklist = Checklist::create($data);

        $trip->update([
            'receive_delivery_note_id' => $checklist->id,
            'stage' => Core::OFFLOADING
        ]);

        $truck->update(['location' => Core::nextStep($truck->location)]);

        $delnote = $trip->deliveryNote;
        $delnote->fields = json_decode($delnote->fields);
        $trip->deliveryNote = $delnote;
        $delnote = $trip->receiveNote;
        $delnote->fields = json_decode($delnote->fields);
        $trip->receiveNote = $delnote;

        $printout = view('printouts.deliverynote')->with('trip', $trip)->render();

        return Response::json([
            'message' => 'Successfully completed loading.',
            'shouldPrint' => true,
            'printout' => $printout
        ]);
    }

    public function offloading(Request $request, $id)
    {
        $truck = TruckFactory::findOrFail($id);

        $trip = Trip::where('truck_id', $truck->id)
            ->where('stage', Core::OFFLOADING)
            ->where('is_complete', false)
            ->orderBy('id', 'desc')
            ->first();

        TruckFactory::createBilling($trip);

        $trip->update([
            'stage' => Core::IN_YARD,
            'is_complete' => true
        ]);

        $truck->update(['location' => Core::nextStep($truck->location)]);

        return Response::json([
            'message' => 'Successfully completed offloading.',
            'trucks' => TruckFactory::atLocation('offloading'),
        ]);
    }

    public function inYard(Request $request, $id)
    {
        $truck = TruckFactory::findOrFail($id);

        $truck->update(['location' => Core::nextStep($truck->location)]);

        return Response::json([
            'message' => 'Successfully completed trip.',
            'trucks' => TruckFactory::atLocation('offloading'),
        ]);
    }

    public function progress(Request $request, $id)
    {
        $truck = TruckFactory::findOrFail($id);
        $shouldPrint = false;
        $printout = '';
        switch ($truck->location) {
            case Core::PRE_LOADING:
                $trip = $this->inPreLoading($request, $truck);
                if ($trip->preLoadingChecklist->status == 'Approved' &&
                    $trip->preLoadingChecklist->suitable_for_loading) {
                    $shouldPrint = true;
                    $printout = view('printouts.loadingorder')->with('trip', $trip)->render();
                }
                break;
        }



//        $nextStep = Core::nextStep($truck->location);
//
//        if ($nextStep == Core::IN_YARD) {
//            TruckFactory::createBilling($truck);
//        }

//        $truck->update(['location' => $nextStep]);

        return Response::json([
            'message' => 'Successfully completed action.',
            'shouldPrint' => $shouldPrint,
            'printout' => $printout
        ]);
    }

    private function inPreLoading(Request $request, Truck $truck)
    {
        $data = $request->all();
        $data['driver_id'] = $truck->driver_id;
        $data['truck_id'] = $truck->id;
        $data['type'] = Checklist::class;
        $data['from_station'] = $truck->contract->route->source;
        $data['to_station'] = $truck->contract->route->destination;
        $data['fields'] = json_encode($data['items']);
        $data['inspector_id'] = $data['user_id'];
        $data['supervisor_id'] = $data['user_id'];
        $data['suitable_for_loading'] = $data['suitable_for_loading'] == 1;
        $data['for_date'] = Carbon::now();

        $data['status'] = 'Approved'; // TODO: REMOVE THIS

        $trip = Trip::where('trip_date', Carbon::now())
            ->where('truck_id', $truck->id)
            ->where('is_complete', false)
            ->first();


        if (! $trip) {
            $checklist = Checklist::create($data);

            $trip = Trip::create([
                'truck_id' => $truck->id,
                'contract_id' => $truck->contract->id,
                'driver_id' => $truck->driver_id,
                'route_id' => $truck->contract->route_id,
                'source' => $truck->contract->route->source,
                'destination' => $truck->contract->route->destination,
                'pre_loading_checklist_id' => $checklist->id,
                'trip_date' => Carbon::now(),
                'stage' => Core::PRE_LOADING,
            ]);
        }

        $trip->preLoadingChecklist->update($data);

        if ($trip->preLoadingChecklist->status == 'Approved') { // Include authentication to check if current user can approve
            $truck->update(['location' => Core::nextStep($truck->location)]);
            $trip->update(['stage' => Core::LOADING]);
        }

        return $trip;
    }

    public function getTrip($id)
    {
        $truck = TruckFactory::findOrFail($id);

        $trip = Trip::with([
            'preLoadingChecklist', 'deliveryNote', 'truck.trailer', 'driver', 'contract.client', 'route'
        ])
            ->where('trip_date', Carbon::now())
            ->where('truck_id', $truck->id)
            ->where('is_complete', false)
            ->first();

        if (! $trip) {
            return Response::json([
                'status' => 'success',
                'checklist' => false,
            ]);
        }

        $checklist = $trip->preLoadingChecklist;
        $checklist->fields = json_decode($checklist->fields);
        $trip->preLoadingChecklist = $checklist;
        $trip->trip_date = Carbon::parse($trip->trip_date)->format('d F Y');

        return Response::json([
            'status' => 'success',
            'trip' => $trip,
            'supervisor' => false,
            'inspector' => true,
        ]);
    }
}
