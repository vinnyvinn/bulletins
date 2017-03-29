<?php

namespace SmoDav\Factory;

use function abort;
use App\Contract;
use App\Truck;
use DB;
use SmoDav\SAGE\Cashier;
use function strtoupper;

class TruckFactory
{

    public static function allAssignedDrivers()
    {
        return Truck::whereNotNull('driver_id')->get(['driver_id']);
    }

    public static function all($columns = ['*'])
    {
        return Truck::with(['driver'])->get($columns);
    }

    public static function findOrFail($id)
    {
        return Truck::with(['driver'])->findOrFail($id);
    }

    public static function create($attributes)
    {
        $attributes['plate_number'] = strtoupper($attributes['plate_number']);
        $attributes['project_id'] = self::createInSAGE($attributes);

        return Truck::create($attributes);
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
            'ProjectDescription' => $fields['plate_number'] . ' carrying a maximum of ' . $fields['max_load'] . ' Tonnes',
            'MasterSubProject' => $master->ProjectCode . '>' . $fields['plate_number'],
        ];
    }

    public static function update($attributes, $id)
    {
        $truck = self::findOrFail($id);
        $truck->update($attributes);
        DB::table('Project')->where('ProjectLink', $truck->project_id)->update(self::mapSAGEFields($attributes));

        return $truck;
    }

    public static function unassigned()
    {
        return Truck::whereNull('contract_id')->whereNotNull('driver_id')->get();
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

    public static function createBilling(Truck $truck)
    {
        $contract = $truck->contract;
        $route = $contract->route;
        $quantity = $route->distance;

        if ($contract->rate == Contract::PER_KM) {
            $quantity = $route->distance;
        }

        if ($contract->rate == Contract::PER_HR) {
            $quantity = $route->distance;
        }

        if ($contract->rate == Contract::PER_TONNE) {
            $quantity = $route->distance;
        }

        Cashier::invoice($contract, $contract->amount, $quantity, $truck->project_id);
    }


}
