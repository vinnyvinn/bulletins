<?php

namespace App\Support;

class Core
{
    const ACTIVE = 'Active';
    const INACTIVE = 'Inactive';
    const WORKSHOP = 'Central Truck Yard';
    const AWAITING_ALLOCATION = 'Awaiting Allocation';
    const PRE_LOADING = 'Pre-Loading';
    const LOADING = 'Loading';
    const ENROUTE = 'Enroute';
    const OFFLOADING = 'Offloading';
    const IN_YARD = 'In Yard';
    const INCIDENT = 'Incident';

    const TRUCK = 'Truck';
    const TRAILER = 'Trailer';
    const VAN = 'Van';
    const OTHER = 'Other';
    const COMPACT = 'Compact';
    const BUS = 'Bus';
    const BULLDOZER = 'Bulldozer';
    const CRANE = 'Crane';

    const DOUBLE_CAB = 'DoubleCab Pick-up';
    const PICKUP = 'Pickup';
    const EXCAVATOR = 'Excavator';
    const FUEL_TANKER = 'Fuel Tanker';
    const LORRY = 'Lorry';
    const MINI_BUS = 'Mini-Bus';
    const MOTORCYCLE = 'Motor Cycle';
    const MOTOR_GRADER = 'Motor Grader';
    const PRIME = 'Motor Grader';
    const ROLLER = 'Roller';
    const SALOON = 'Saloon';
    const STATION_WAGON = 'Station Wagon';
    const WHEEL_LOADER = 'Wheel-Loader';

    public static function nextStep($currentStep)
    {
        switch ($currentStep) {
            case self::AWAITING_ALLOCATION:
                return self::PRE_LOADING;
            case self::PRE_LOADING:
                return self::LOADING;
            case self::LOADING:
                return self::ENROUTE;
            case self::ENROUTE:
                return self::OFFLOADING;
            case self::OFFLOADING:
                return self::IN_YARD;
            case self::IN_YARD:
                return self::PRE_LOADING;
            default:
                return self::AWAITING_ALLOCATION;
        }
    }
}
