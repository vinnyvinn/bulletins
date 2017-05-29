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
use SmoDav\SAGE\Cashier;
use function strtoupper;

class TruckFactory
{

    public static function atLocation($location)
    {
        switch ($location) {
            default:
            case 'pre-loading':
                return Truck::with(['driver'])->preLoading()->get();
                break;
            case 'loading':
                return Truck::with(['driver'])->loading()->get();
                break;
            case 'enroute':
                return Truck::with(['driver'])->enroute()->get();
                break;
            case 'offloading':
                return Truck::with(['driver'])->offloading()->get();
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
        return Truck::with(['driver', 'trailer'])->get($columns);
    }

    public static function findOrFail($id)
    {
        return Truck::with(['driver'])->findOrFail($id);
    }

    public static function create($attributes)
    {
        $attributes['plate_number'] = strtoupper($attributes['plate_number']);
        $attributes['project_id'] = self::createInSAGE($attributes);
        $truck = new Truck();
        return $truck->fill($attributes)->save();
    }

    private static function createInSAGE($attributes)
    {
        return DB::table('Project')->insertGetId(self::mapSAGEFields($attributes));
    }

    private static function mapSAGEFields($fields)
    {
        $masterProject = env('MASTER_PROJECT', 1);
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
            'ProjectDescription' => $fields['plate_number'] . ' carrying a maximum of ' . $fields['max_load'] . ' KGs',
            'MasterSubProject' => $master->ProjectCode . '>' . $fields['plate_number'],
        ];
    }

    public static function update($attributes, $id)
    {
        $truck = self::findOrFail($id);
        if (isset($attributes['trailer_id'])) {
            if ($truck->trailer_id != $attributes['trailer_id']) {
                if ($truck->trailer_id) {
                    Trailer::findOrFail($truck->trailer_id)->update(['truck_id' => null]);
                }

                if ($attributes['trailer_id']) {
                    Trailer::findOrFail($attributes['trailer_id'])->update(['truck_id' => $truck->id]);
                }
            }
        }

        $truck->fill($attributes)->save();

        DB::table('Project')->where('ProjectLink', $truck->project_id)->update(self::mapSAGEFields($attributes));

        return $truck;
    }

    public static function unassigned()
    {
        return Truck::whereNull('contract_id')
            ->whereNotNull('driver_id')
            ->whereNotNull('trailer_id')
            ->get();
    }

    public static function assigned()
    {
        return Truck::with(['driver', 'contract' => function ($query) {
            $query->select(['id', 'name', 'client_id', 'start_date', 'end_date']);
        }, 'contract.client' => function ($query) {
            return $query->select(['DCLink','Name', 'Account']);
        }])
            ->whereNotNull('contract_id')
            ->get(['id', 'plate_number', 'max_load', 'contract_id', 'driver_id', 'location']);
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
