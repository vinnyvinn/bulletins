<?php

use Illuminate\Database\Seeder;
use SmoDav\Models\CarriagePoint;

class CarriagePointsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $points = [
            'KISHUSHE MINING', 'BAMBURI', 'NAIROBI', 'ATHI RIVER', 'BAMBURI MSA', 'KOKOTONI', 'PORT MOMBASA', 'KIBINI',
            'KONZA', 'KITENGELA', 'HIMA CEMENT TZ', 'KASESE UGANDA', 'TORORO UGANDA', 'KASSAM RAMJI', 'MBARAKI MOMBASA',
            'BAMBURI MOMBASA', 'HIMA CEMENT UGANDA', 'KASESE UGANDA', 'TORORO UGANDA', 'KASSAM RAMJI',
            'KISHUSHE MINING', 'GAPCO MOMBASA', 'NCPB MOI S', 'KALOLENI ATHI RIVER MINING', 'SHREEJI',
            'CORRUGATED MIKINDANI', 'CORRUGATED MIKINDANI', 'CORRRUGATED KOKOTONI', 'NYALI BOMA', 'GRAINBULK MOMBASA',
            'SAJ CERAMICS ATHI RIVER', 'AQUALINE DISTRIBUTOR ATHI-RIVER', 'MWAKIRUNGE',
            'SAMEER AFRICA LIMITED ATHI RIVER', 'AQUILINE-ATHIRIVER', 'KAPA OIL REFINERIES - MLOLONGO',
            'ATHI RIVER MINING - ATHI RIVER PLANT', 'MLOLONGO', 'WORLD FOOD PROGRAMME MSA ROADTAINERS', 'DAGORETTI',
            'AWASI KISUMU', 'VAPCO CONSTRUCTIONS NYALI', 'ABYSSINIA KISUMU', 'EAPCC', 'EAPCC', 'shimanzi', 'ELBAGON',
            'MALABA', 'VIPINGO', 'MORTREX', 'Mois Bridge', 'KITUI', 'MAKUENI', 'PORT MOMBASA', 'PORT MOMBASA',
            'MULTIPLE MSA', 'MIRITINI', 'EXPORT TRADING', 'KAMPALA', 'CHANGAMWE', 'CHANGAMWE', 'Export trading Bonje',
            'BUZEKI KOKOTONI', 'KITAI TANZANIA', 'KITAI TANZANIA', 'GONGONI MALINDI'
        ];

        $toInsert = [];

        foreach ($points as $point) {
            $toInsert [] = [
                'name' => $point
            ];
        }

        CarriagePoint::insert($toInsert);
    }
}
