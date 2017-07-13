<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = [
            'contract', 'contract-template', 'journey', 'inspection', 'delivery', 'mileage', 'fuel', 'route-card',
            'job-card', 'parts-requisition',
            'users'
        ];

        $hasApproval = [
            'contract', 'journey', 'fuel', 'mileage'
        ];

        $permission = [];

        foreach ($modules as $module) {
            $title = ucwords(str_replace('-', ' ', $module));

            $permission[] = [
                'permission' => 'Create ' . $title,
                'slug' => 'create-' . $module,
                'description' => 'Allow the user to create the ' . $title
            ];

            $permission[] = [
                'permission' => 'View ' . $title,
                'slug' => 'view-' . $module,
                'description' => 'Allow the user to view the ' . $title
            ];

            $permission[] = [
                'permission' => 'Edit ' . $title,
                'slug' => 'edit-' . $module,
                'description' => 'Allow the user to edit the ' . $title
            ];

            $permission[] = [
                'permission' => 'Delete ' . $title,
                'slug' => 'delete-' . $module,
                'description' => 'Allow the user to delete the ' . $title
            ];

            if (in_array($module, $hasApproval)) {
                $permission [] = [
                    'permission' => 'Approve ' . $title,
                    'slug' => 'approve-' . $module,
                    'description' => 'Allow the user to approve the ' . $module
                ];
            }
        }

        Permission::insert($permission);
    }
}
