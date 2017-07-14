<?php

namespace SmoDav\Factory;

use function abort;
use App\Contract;
use App\Support\Core;
use App\Trailer;
use App\Trip;
use App\Truck;
use Carbon\Carbon;
use DB;
use function json_decode;
use function round;
use SmoDav\Models\Vehicle;
use SmoDav\SAGE\Cashier;
use function strtoupper;

class VehicleFactory
{

    public static function atLocation($location)
    {
        switch ($location) {
            default:
            case 'pre-loading':
                return Truck::with(['trailer'])->preLoading()->get();
                break;
            case 'loading':
                return Truck::with(['trailer'])->loading()->get();
                break;
            case 'enroute':
                return Truck::with(['trailer'])->enroute()->get();
                break;
            case 'offloading':
                return Truck::with(['trailer'])->offloading()->get();
                break;
            case 'in-yard':
                return Truck::inYard()->get();
                break;
        }
    }

    public static function allAssignedDrivers($truckId = null)
    {
        return Truck::whereNotNull('driver_id')
            ->when($truckId, function ($query) use ($truckId) {
                return $query->where('id', '<>', $truckId);
            })
            ->get(['driver_id']);
    }

    public static function withTrash($columns = ['*'])
    {
        return Truck::withTrashed()->get($columns);
    }

    public static function all($columns = ['*'])
    {
        return Vehicle::with(['trailer', 'driver', 'make', 'model'])->get($columns);
    }

    public static function findOrFail($id)
    {
        return Vehicle::with(['trailer', 'driver', 'make', 'model'])->findOrFail($id);
    }

    public static function create($attributes)
    {
        $attributes['plate_number'] = strtoupper($attributes['plate_number']);
        $attributes['project_id'] = self::createInSAGE($attributes);
        $truck = Vehicle::create($attributes);

        unset($attributes['_method'], $attributes['_token'], $attributes['driver'], $attributes['trailer']);

        foreach ($attributes as $attribute => $value) {
            $truck->{$attribute} = $value;
        }

        $truck->save();

        return $truck;
    }

    private static function createInSAGE($attributes)
    {
        return DB::table('Project')->insertGetId(self::mapSAGEFields($attributes));
    }

    private static function mapSAGEFields($fields)
    {
        $masterProject = 1;
        $master = DB::table('Project')->where('ProjectLink', $masterProject)->first();

        if (! $master) {
            abort(404);
        }

        return [
            'SubProjectOfLink' => $masterProject,
            'ActiveProject' => 1,
            'ProjectLevel' => 2,
            'Project_iBranchID' => 0,
            'ProjectCode' => $fields['plate_number'],
            'ProjectName' => $fields['plate_number'],
            'ProjectDescription' => $fields['plate_number'],
            'MasterSubProject' => $master->ProjectCode . '>' . $fields['plate_number'],
        ];
    }

    public static function update($attributes, $id)
    {
        $truck = self::findOrFail($id);
        if (isset($attributes['trailer_id'])) {
            if ($truck->trailer_id != $attributes['trailer_id']) {
                if ($truck->trailer_id) {
                    Vehicle::findOrFail($truck->trailer_id)->update(['truck_id' => null]);
                }

                if ($attributes['trailer_id']) {
                    Vehicle::findOrFail($attributes['trailer_id'])->update(['truck_id' => $truck->id]);
                }
            }
        }

        unset(
            $attributes['_method'], $attributes['_token'], $attributes['driver'], $attributes['trailer'],
            $attributes['make'], $attributes['model']
        );

        foreach ($attributes as $key => $value) {
            $truck->{$key} = $value;
        }

        $truck->save();

        DB::table('Project')->where('ProjectLink', $truck->project_id)->update(self::mapSAGEFields($attributes));

        return $truck;
    }

    public static function unassigned()
    {
        return Truck::whereNull('contract_id')
            ->whereNotNull('trailer_id')
            ->get();
    }

    public static function assigned()
    {
        return Truck::with(['contract' => function ($query) {
            $query->select(['id', 'name', 'client_id', 'start_date', 'end_date']);
        }, 'contract.client' => function ($query) {
            return $query->select(['DCLink','Name', 'Account']);
        }])
            ->whereNotNull('contract_id')
            ->get(['id', 'plate_number', 'max_load', 'contract_id', 'location']);
    }

    public static function createBilling(Trip $trip)
    {
        $truck = $trip->truck;
        $contract = $truck->contract;
        $route = $contract->route;
        $quantity = $route->distance;
        $delNote = $trip->deliveryNote;
        $receiveNote = $trip->receiveNote;
        $receiveNote->fields = json_decode($receiveNote->fields);

        if ($contract->rate == Contract::PER_KM) {
            $quantity = $route->distance;
        }

        if ($contract->rate == Contract::PER_HR) {
            $quantity = round((Carbon::parse($delNote->createdAt)->diffInMinutes($receiveNote->createdAt)) / 60);
        }

        if ($contract->rate == Contract::PER_TONNE) {
            $quantity = $receiveNote->fields->gross_weight - $receiveNote->fields->tare_weight;
        }

        Cashier::invoice($contract, $contract->amount, $quantity, $truck->project_id);
    }
}
