<?php

use Illuminate\Database\Seeder;
use SmoDav\Models\WorkshopInspectionCheckList;
use SmoDav\Models\WorkshopJobOperation;
use SmoDav\Models\WorkshopJobTask;
use SmoDav\Models\WorkshopJobType;

class WorkshopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedChecklist();
        $this->seedServiceA();
        $this->seedServiceB();
        $this->seedServiceC();
        $this->seedJobTypes();
    }

    private function seedChecklist()
    {
        WorkshopInspectionCheckList::insert([
            ['name' => 'Greasing'],
            ['name' => '5th Wheel Check'],
            ['name' => 'General Inspection'],
            ['name' => 'Battery Check'],
        ]);
    }

    private function seedServiceA()
    {
        $type = WorkshopJobType::create([
            'service_type' => 'Service Job', 'name' => 'Service A'
        ]);

        $operation = WorkshopJobOperation::create([
            'workshop_job_type_id' => $type->id,
            'name' => 'Service A Operations'
        ]);

        $items = [
            'Oil Filter(changing)', 'Diesel Filter(changing)', 'Engine Oil(changing)',
            'Water Separator filter(changing)', 'Copper Washer', 'Greasing', 'Carry our brake adjustment(UD and TATA)',
            'AIR Filter(Replace after 60000 km)', 'Air drier filter(Replace after 60000km)', 'Steering Oil',
            'Gear box oil', 'Differential Oil', 'Brake pad/linningwear', 'Brake disc wear', 'Brake calipers',
            'Engine breather system', 'All fuel pipes', 'All air pipes', 'Cooling system', 'Check V-arm bushes',
            'Fan belt and Tensioner', 'Wiper blades operation/wiper water', 'Tighten halfmoon bolts',
            'Check condition of spring bushes', 'Check condition of shock', 'Check condition of Kingpin',
            'Check control arm bushes', 'Check operation of external lights', 'Check operation of warning lights',
            'Check operation of electrical switches',
        ];

        $toInsert = [];
        foreach ($items as $item) {
            $toInsert [] = [
                'workshop_job_operation_id' => $operation->id,
                'name' => ucfirst(strtolower($item)),
            ];
        }

        WorkshopJobTask::insert($toInsert);
    }

    private function seedServiceB()
    {
        $type = WorkshopJobType::create([
            'service_type' => 'Service Job', 'name' => 'Service B'
        ]);

        WorkshopJobOperation::create([
            'workshop_job_type_id' => $type->id,
            'name' => 'Service B Operations'
        ]);
    }

    private function seedServiceC()
    {
        $type = WorkshopJobType::create([
            'service_type' => 'Service Job', 'name' => 'Service C'
        ]);

        $operation = WorkshopJobOperation::create([
            'workshop_job_type_id' => $type->id,
            'name' => 'Service C Operations'
        ]);

        $items = [
            'Check 5th wheel kingdom', 'Check Tipping jack pin and bolt', 'Check hydraulic tipping jack leakage',
            'Check control arm bush', 'Check condition of 5th wheel', 'Check U-bolt', 'Check centre bolt',
            'Check equiliser bush and pin all axles', 'Check brake linings and drums', 'Carry out break adjustment',
            'Check relay trailer valve on all axles', 'Check Emergency Relay valve',
            'Check pressure leakage for brake system', 'Carry out greasing on trailer',
            'Check landing jack operation', 'Check all wheel stud', 'Check bearing play on wheel bulb',
            'Carry out wheel alignment', 'Check licence holder and trailer licence', 'Check reflector and chevrons',
            'Check number plate light', 'Check lighting system', 'Check trailer door functioning',
            'Check body and chassis for crack', 'Check if all hangers are present',
            'Check skid  roller and side plates', 'Check tarpualin condition',
            'Check battery tray for loosenes and crack', 'Check battery tray cover and locking system',
            'Check battery clamp', 'Check battery cables and terminal', 'Check terminal for cleanliness',
            'Check battery for cleanliness', 'Check battery water level & strength of acid',
            'Check if the battery tray is levelled', 'Check battery charging system',
            'Recorded mileage and date fitted', 'Check battery cables on earth point',
            'Check battery cable connector looseness', 'Do not repair burnt terminals',
            'Ensure that battery is in right size for vehicle', 'Measure voltage on batteries',
        ];

        $toInsert = [];
        foreach ($items as $item) {
            $toInsert [] = [
                'workshop_job_operation_id' => $operation->id,
                'name' => ucfirst(strtolower($item)),
            ];
        }

        WorkshopJobTask::insert($toInsert);
    }

    private function seedJobTypes()
    {
        $types = [
            'MECHANICAL' => [
                'MUD GUARD', 'GEARBOX', 'GEARS', 'PRESSURE LINE', 'ENGINE', 'STEERING', 'MAINTAINANCE', 'BRAKES',
                'DRIVE TRAIN', 'SUSPENSION', 'Fuel line system', 'AIR PRESSURE SYSTEM', 'TRAILER', 'COOLING SYSTEM',
                'FUEL TANK', 'CHASSIS', 'P.T.O SYSTEM', 'CLUTCH', 'EXHAUST', 'AIR INTAKE', 'CABIN', 'TANKER',
                'TIPPER', 'BACK HOE', 'WHEELS', 'Brakes', 'RAIL PRESSURE', 'EDC UNIT', 'WIPERS', 'GENERAL', 'KAREN',
                'TIPPING', 'ENGINEERING', 'cabin', 'Fifth Wheel', 'EBS', 'HYDRAULICS', 'DIFF', 'bucket',
                'Speed Governor'
            ],
            'ELECTRICAL' => [
                'TRAILER', 'BRAKES', 'WIPERS', 'BATTERY', 'WIRING', 'CABIN', 'ENGINE', 'GEARBOX', 'SUSPENSION',
                'LIGHTS', 'EDC UNIT', 'STARTER MOTOR', 'FAN BELT/ TENSIONER', 'GPRS SYSTEM',
            ],
            'WELDING' => [
                'FUEL TANK', 'TANKER', 'EXHAUST', 'CHASSIS', 'MUD GUARD', 'BODY', 'CABIN', 'Fifth wheel', 'BUMPER',
                'WATER SEPARATOR', 'TIPPER', 'GENERAL', 'AIR INTAKE', 'BATTERY', 'cooling system', 'AIR TANK',
                'SUSPENSION', 'TRAILER', 'BRAKES',
            ],
            'BODYSHOP' => [
                'CABIN', 'TRAILER', 'MUD GUARD', 'GENERAL', 'PAINT', 'PANEL BEATING',
            ],
            'TYRES' => ['TYRES'],
            'DIAGNOSIS' => [
                'ABS CODE', 'Read codes', 'RAIL PRESSURE',
            ],
            'HYDRAULIC' => [
                'P.T.O', 'HYDRAULIC TANK', 'BOTTLE JACK', 'Hydraulic pipe',
            ],
            'GENERAL' => [
                'YARD', 'OFFICES', 'GREASING', 'Air Cleaner Filter Element', 'General Check-up',
            ],
            'BREAK DOWN' => ['ATTEND BREAKDOWN'],
        ];

        $toInsert = [];

        foreach ($types as $type => $operations) {
            $createdType = WorkshopJobType::create([
                'service_type' => 'Normal Job', 'name' => ucwords(strtolower($type))
            ]);

            foreach ($operations as $operation) {
                $toInsert [] = [
                    'workshop_job_type_id' => $createdType->id,
                    'name' => ucfirst(strtolower($operation))
                ];
            }
        }

        WorkshopJobOperation::insert($toInsert);
    }
}
