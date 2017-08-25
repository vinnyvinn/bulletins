<?php

use Illuminate\Database\Seeder;
use SmoDav\Models\Make;
use SmoDav\Models\Model;

class MakesAndModelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $makes = [
            [
                'name' => '40ft',
                'models' => ['NA']
            ],
            [
                'name' => 'BENZ',
                'models' => ['E 280']
            ],
            [
                'name' => 'Bhachu',
                'models' => ['Bhachu']
            ],
            [
                'name' => 'CUMMINGS',
                'models' => ['C330-DD5-ENCLOSED']
            ],
            [
                'name' => 'BMW',
                'models' => ['X5']
            ],
            [
                'name' => 'BOMBAG',
                'models' => ['ROLLER']
            ],
            [
                'name' => 'CAT',
                'models' => ['980C', '320', '980', '936E', '950']
            ],
            [
                'name' => 'DUMPER BENFORD',
                'models' => ['KHMA 040A', 'KHMA 041A', 'KHMA 043A', 'KHMA 045A', 'KHMA 042A']
            ],
            [
                'name' => 'DUMPER TH',
                'models' => ['KHMA 026A']
            ],
            [
                'name' => 'ESCORT',
                'models' => ['HDR A14']
            ],
            [
                'name' => 'FAW',
                'models' => ['CA 3223', 'CA 3320', 'J5-280']
            ],
            [
                'name' => 'FINTEC',
                'models' => ['A', 'B']
            ],
            [
                'name' => 'FORD',
                'models' => ['RANGER DOUBLECAB', 'RANGER PICKUP']
            ],
            [
                'name' => 'HYDRA',
                'models' => ['14T']
            ],
            [
                'name' => 'HYUNDAI',
                'models' => [
                    '305', '780', '320 LC7', '180', '320 LC3', '450 LC', 'HL 780-7A', '210', '450 LC3', '450LC7'
                ]
            ],
            [
                'name' => 'ISUZU',
                'models' => ['CANTER', 'FVZ', 'TA-AZR606KAI', 'NKR', 'EXZ']
            ],
            [
                'name' => 'JCB',
                'models' => ['JCB', '3CX-ECO SITEMASTER']
            ],
            [
                'name' => 'JEEP',
                'models' => ['CHEROKEE']
            ],
            [
                'name' => 'LANDROVER',
                'models' => ['DEFENDER', 'DISCOVERY']
            ],
            [
                'name' => 'LEIBBHERR',
                'models' => ['514', '914', '954']
            ],
            [
                'name' => 'MAN',
                'models' => ['TGA', 'TGA 410', 'TGA 411', 'TGA 430', 'TGA 460', 'TGA 480']
            ],
            [
                'name' => 'MAZDA',
                'models' => ['MAZDA']
            ],
            [
                'name' => 'Menci',
                'models' => ['Menci']
            ],
            [
                'name' => 'Mercedes-Benz',
                'models' => [
                    'ATEGO', 'Axor 1843', 'Axor 2540', 'Axor 1843', 'Axor 2543', 'SK', 'Actros 2540', 'Actros 3340',
                    'Actros 2535', 'Axor 2540', 'E280', '2638',
                ]
            ],
            [
                'name' => 'Mitsubishi',
                'models' => ['FH', 'ROSA', 'LANCER']
            ],
            [
                'name' => 'Nissan',
                'models' => [
                    'UD CWB450', 'Navara', 'HOMMY', 'TD 27', 'HARDBODY', 'X-Trail', 'PICK UP', 'Gloria', 'URVAN',
                    'WING ROAD'
                ]
            ],
            [
                'name' => 'OCEAN',
                'models' => ['OCEAN']
            ],
            [
                'name' => 'POWERPLUS',
                'models' => ['968T', 'CV 302D', '14E', 'D8', 'PP 140G']
            ],
            [
                'name' => 'Randon',
                'models' => ['Randon']
            ],
            [
                'name' => 'RANGE ROVER',
                'models' => ['SPORT', 'VOGUE', 'V8', 'HS1', 'HSE 4.6']
            ],
            [
                'name' => 'Scania',
                'models' => ['P310', 'P360', 'R380', 'R420', 'P410', 'P360']
            ],
            [
                'name' => 'SDMO',
                'models' => ['J88K']
            ],
            [
                'name' => 'SHACMAN',
                'models' => ['SX 3254']
            ],
            [
                'name' => 'SKYGO',
                'models' => ['125CC']
            ],
            [
                'name' => 'SONGYL',
                'models' => ['SY125', 'SY150']
            ],
            [
                'name' => 'TAIWAN',
                'models' => ['TAIWAN']
            ],
            [
                'name' => 'TATA',
                'models' => ['NOVUS 3034']
            ],
            [
                'name' => 'Daewoo',
                'models' => ['125']
            ],
            [
                'name' => 'TOYOTA',
                'models' => [
                    'HIACE', 'LANDCRUISER V8', 'SUCCEED', 'LANDCRUISER', 'HILUX', 'CE 107', 'LANDCRUISER V9',
                    'PROBOX', 'PRADO', 'COROLLA', 'DX', 'GRAND HIACE', 'MARK-X', 'SALOON'
                ]
            ],
            [
                'name' => 'VOLKSWAGEN',
                'models' => ['Toureg', 'GT']
            ],
        ];

        DB::transaction(function () use ($makes) {
            foreach ($makes as $make) {
                $makeModel = Make::create(['name' => \ucwords(\strtolower($make['name']))]);
                $models = \array_map(function ($model) use ($makeModel) {
                    return [
                        'name' => \ucwords(\strtolower($model)),
                        'make_id' => $makeModel->id
                    ];
                }, $make['models']);

                Model::insert($models);
            }
        });
    }
}
