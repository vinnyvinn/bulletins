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

