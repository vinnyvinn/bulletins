<?php

use Illuminate\Database\Seeder;
use SmoDav\Models\APIIntegration;

class IntegrationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        APIIntegration::create([
            'name' => APIIntegration::PAYROLL,
            'endpoint' => '',
            'grant_type' => 'authorization_code',
            'client_id' => '',
            'client_secret' => '',
            'redirect_uri' => '/integration/payroll/1',
            'code' => '',
        ]);
    }
}
