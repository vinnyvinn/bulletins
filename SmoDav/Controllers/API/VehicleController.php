<?php

namespace SmoDav\Controllers\API;

use App\Checklist;
use App\Driver;
use App\Http\Controllers\Controller;
use App\Http\Requests\TruckRequest;
use App\Http\Requests\UploadRequest;
use App\Support\Core;
use App\Trailer;
use App\Truck;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use function intval;
use function json_encode;
use Response;
use SmoDav\Factory\TruckFactory;
use SmoDav\Factory\VehicleFactory;
use SmoDav\Models\Make;
use SmoDav\Models\Vehicle;
use SmoDav\Support\Excel;
use function str_replace;
use SmoDav\Models\Journey;
use App\Fuel;
use SmoDav\Models\LocalShunting\LSFuel;
use SmoDav\Models\LocalShunting\LSDelivery;
use SmoDav\Support\Constants;


class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        return Response::json([
            'trucks' => VehicleFactory::all()
        ]);
    }

    public function create()
    {
        if (request('truck_id')) {
            return Response::json([
                'drivers' => Driver::whereDoesntHave('vehicle', function ($builder) {
                    return $builder->where('id', '<>', request('truck_id'));
                })->get(),
                'trailers' => Vehicle::typeTrailer()->whereNull('truck_id')
                    ->orWhere('truck_id', request('truck_id'))
                    ->get(['id', 'plate_number']),
                'makes' => Make::with(['models'])->get(['id', 'name']),
                'truck' => VehicleFactory::findOrFail(request('truck_id')),
            ]);
        }

        return Response::json([
            'drivers' => Driver::doesntHave('vehicle')->get(),
            'trailers' => Vehicle::typeTrailer()->whereNull('truck_id')->get(['id', 'plate_number']),
            'makes' => Make::with(['models'])->get(['id', 'name']),
        ]);
    }

    public function store(TruckRequest $request)
    {
        $data = $request->all();
        foreach ($request->all() as $key => $item) {
            if ($request->hasFile($key)) {
                $extension = $request->file($key)->getClientOriginalExtension();
                $filename = time().".".$extension;
                $request->file($key)->move(public_path('uploads'), $filename);
                $data[$key] = $filename;
            }
        }

        VehicleFactory::create($data);

        return Response::json([
            'message' => 'Successfully added new truck.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     *
     */
    public function show($id)
    {
        return Response::json([
            'truck' => VehicleFactory::findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TruckRequest $request
     * @param                           $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(TruckRequest $request, $id)
    {
        $data = $request->all();

        foreach ($request->all() as $key => $item) {
            $data[$key] = $item;
            if ($request->hasFile($key)) {
                $extension = $request->file($key)->getClientOriginalExtension();
                $filename = time().".".$extension;
                $request->file($key)->move(public_path('uploads'), $filename);
                $data[$key] = $filename;
            }
        }

        VehicleFactory::update($data, $id);

        return Response::json([
            'message' => 'Successfully updated truck.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Truck  $truck
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Vehicle::where('id', $id)->delete();

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully deleted truck',
            'trucks' => VehicleFactory::all()
        ]);
    }

    public function progress(Request $request, $id)
    {
        $truck = TruckFactory::findOrFail($id);
        $data = $request->all();
        $data['driver_id'] = $truck->driver_id;
        $data['truck_id'] = $truck->id;
        $data['truck_id'] = $truck->id;
        $data['type'] = Checklist::class;
        $data['from_station'] = $truck->contract->route->source;
        $data['to_station'] = $truck->contract->route->destination;
        $data['fields'] = json_encode($data['items']);
        $data['inspector_id'] = 1;
        $data['supervisor_id'] = 1;
        $data['suitable_for_loading'] = $data['suitable_for_loading'] == 1;

        Checklist::create($data);

        $nextStep = Core::nextStep($truck->location);

        if ($nextStep == Core::IN_YARD) {
            TruckFactory::createBilling($truck);
        }

        $truck->update(['location' => $nextStep]);

        return Response::json([
            'message' => 'Successfully moved to stage ' . $nextStep,
            'trucks' => TruckFactory::assigned()
        ]);
    }

    public function getAtStage($id)
    {
        return Response::json([
            'trucks' => TruckFactory::atLocation($id)
        ]);
    }

    public function import(UploadRequest $request)
    {
        $file = $request->file('uploaded_file');
        if (! Excel::validateExcel($file)) {
            return Response::json([
                'status' => 'error',
                'message' => 'Please select a valid import file of type XLS or XLSX.'
            ]);
        }

        $trucks = VehicleFactory::withTrash(['plate_number'])->map(function ($truck) {
            return $truck->plate_number;
        })->toArray();

        $rows = Excel::prepare($file)
            ->usingHeaders([
                'type', 'plate_number', 'make_id', 'model_id', 'max_load'
            ])
            ->whenNull(Excel::EXCLUDE_ROW)
            ->includeColumns([
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ])
            ->excludeRows('plate_number', $trucks)
            ->get();

        $rows = collect($rows)->keyBy('plate_number')->values()->toArray();

        $rows = array_map(function ($row) {
            $row['max_load'] = intval(str_replace(',', '', $row['max_load']));

            return $row;
        }, $rows);

        try {
            foreach ($rows as $row) {
                VehicleFactory::create($row);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return Response::json([
                'status' => 'error',
                'message' => 'Please use the sample file format provided and fill all the required fields.'
            ]);
        }

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully imported trucks.',
            'trucks' => VehicleFactory::all()
        ]);
    }

    public function report($id)
    {
        $truck = Vehicle::where('id', $id)->with(['trailer', 'make', 'model'])->first();

        $activities = [];
        $journeys = Journey::where('truck_id', $id)->with('inspection', 'delivery', 'fuel', 'mileage')->get();


        foreach ($journeys as $journey) {
            $activity = [
                'id'              => $journey->id,
                'activity'        => 'Journey Creation',
                'date'            => $journey->created_at,
                'time'            => $journey->created_at,
                'document_number' => 'JRNY-' . $journey->id,
                'posted_by'       => $journey->user->name,
            ];
            array_push($activities, $activity);

            if ($journey->inspection) {
                $activity = [
                    'id'              => $journey->inspection->id,
                    'activity'        => 'Inspection Done',
                    'date'            => $journey->inspection->created_at,
                    'time'            => $journey->inspection->created_at,
                    'document_number' => 'INSP-' . $journey->inspection->id,
                    'posted_by'       => $journey->inspection->inspector->name,
                ];
                array_push($activities, $activity);
            }

            if ($journey->delivery) {
                $activity = [
                    'id'              => $journey->delivery->id,
                    'activity'        => 'Delivery Note Issue',
                    'date'            => $journey->delivery->created_at,
                    'time'            => $journey->delivery->created_at,
                    'document_number' => 'RKS-' . $journey->delivery->id,
                    'posted_by'       => $journey->delivery->user->name,
                ];
                array_push($activities, $activity);
            }

            if ($journey->fuel) {
                $activity = [
                    'id'              => $journey->fuel->id,
                    'activity'        => 'Fuel Issue',
                    'date'            => $journey->fuel->created_at,
                    'time'            => $journey->fuel->created_at,
                    'document_number' => 'FUEL-' . $journey->fuel->id,
                    'posted_by'       => $journey->fuel->user->name,
                ];
                array_push($activities, $activity);
            }

            if ($journey->mileage) {
                $activity = [
                    'id'              => $journey->mileage->id,
                    'activity'        => 'Mileage Issue',
                    'date'            => $journey->mileage->created_at,
                    'time'            => $journey->mileage->created_at,
                    'document_number' => 'MLG-' . $journey->mileage->id,
                    'posted_by'       => $journey->mileage->user->name,
                ];
                array_push($activities, $activity);
            }


        }

        return Response::json([
            'truck'      => $truck,
            'activities' => $activities,
        ]);
    }

    public function lsfuelcreate($id, $contract)
    {
      $vehicle_ls_fuel = LSFuel::where('vehicle_id', $id)->where('contract_id', $contract)->orderBy('created_at', 'desc')->first();
      if($vehicle_ls_fuel) {
        $last_refuel_time = $vehicle_ls_fuel->created_at;
        $deliveries_since_refuel = count(LSDelivery::where('vehicle_id', $id)
        ->where('contract_id', $contract)
        ->where('status', Constants::OFFLOADED)
        ->get());
      } else {
        $deliveries_since_refuel = 0;
      }

      return Response::json([
      'truck' => Vehicle::findOrFail($id),
      'average_trips' => Vehicle::findOrFail($id)->contract->contractConfig->trips,
      'trips' => $deliveries_since_refuel,
      'fuels' => LSFuel::all()
      ]);
    }
}
