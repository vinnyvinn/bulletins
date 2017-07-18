<?php

use Illuminate\Database\Seeder;
use SmoDav\Models\CargoClassification;
use SmoDav\Models\CargoType;

class CargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classification = [
            [
                'name' => 'Bulk'
            ],
            [
                'name' => 'Local'
            ]
        ];

        $bulkTypes = [
            'SAND', 'CLINKER', 'SLUG', 'SILICA', 'BAUXITE', 'ASPHALT', 'CEMENT', 'BULK LPG', 'COAL', 'COOKING OIL',
            'FINE KUNKUR', 'FUEL OIL DIESEL', 'GRAIN', 'GYPSUM', 'IRON ORE', 'LIMESTONE', 'LUBRICANTS', 'MAIZE',
            'PARTY ACCESSORIES', 'SALT', 'SCREENED KUNKUR', 'SODA CRATES', 'WORLD FOOD', 'FERTILIZER',
            'FERTILIZER GRADE A (23:23:0)', 'FERTILIZER GRADE B (17:17:0)', 'FERTILIZER UREA', 'FERTILIZER CAN',
            'WHEAT'
        ];

        $localTypes = [
            'SCRAB TYRES', 'WASTE', 'REFRACOULAD 95LCC CASTABLES', 'COAL AND RICE',
        ];

        $toImport = [];

        foreach ($bulkTypes as $bulkType) {
            $toImport [] = [
                'cargo_classification_id' => 1,
                'name' => $bulkType
            ];
        }

        foreach ($localTypes as $localType) {
            $toImport [] = [
                'cargo_classification_id' => 2,
                'name' => $localType
            ];
        }

        CargoClassification::insert($classification);
        CargoType::insert($toImport);
    }
}

