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
        $transportModules = [
            'contract', 'contract-template', 'journey', 'inspection', 'delivery', 'mileage', 'fuel', 'route-card',
        ];

        $commonModules = [
            'users'
        ];

        $workshopModules = [
            'job-card', 'parts-requisition',
        ];

        $hasApproval = [
            'contract', 'journey', 'fuel', 'mileage'
        ];

        $permission = [];

        $permission = $this->mapCommonModules($commonModules, $permission, $hasApproval);
        $permission = $this->mapDefaultModules($permission);
        $permission = $this->mapTransportModules($transportModules, $permission, $hasApproval);
        $permission = $this->mapWorkshopModules($workshopModules, $permission, $hasApproval);

        Permission::insert($permission);
    }

    /**
     * @param $commonModules
     * @param $permission
     * @param $hasApproval
     * @return array
     */
    private function mapCommonModules($commonModules, $permission, $hasApproval): array
    {
        $permission[] = [
            'name'        => 'All Rights',
            'group'       => null,
            'slug'        => '*',
            'description' => 'Allow the user perform all actions'
        ];

        foreach ($commonModules as $module) {
            $title = ucwords(str_replace('-', ' ', $module));

            $permission[] = [
                'name'        => 'Create ' . $title,
                'group'       => null,
                'slug'        => 'create-' . $module,
                'description' => 'Allow the user to create the ' . $title
            ];

            $permission[] = [
                'name'        => 'View ' . $title,
                'group'       => null,
                'slug'        => 'view-' . $module,
                'description' => 'Allow the user to view the ' . $title
            ];

            $permission[] = [
                'name'        => 'Edit ' . $title,
                'group'       => null,
                'slug'        => 'edit-' . $module,
                'description' => 'Allow the user to edit the ' . $title
            ];

            $permission[] = [
                'name'        => 'Delete ' . $title,
                'group'       => null,
                'slug'        => 'delete-' . $module,
                'description' => 'Allow the user to delete the ' . $title
            ];

            if (in_array($module, $hasApproval)) {
                $permission [] = [
                    'name'        => 'Approve ' . $title,
                    'group'       => null,
                    'slug'        => 'approve-' . $module,
                    'description' => 'Allow the user to approve the ' . $module
                ];
            }
        }

        return $permission;
    }

    /**
     * @param $workshopModules
     * @param $permission
     * @param $hasApproval
     * @return array
     */
    private function mapWorkshopModules($workshopModules, $permission, $hasApproval): array
    {
        foreach ($workshopModules as $module) {
            $title = ucwords(str_replace('-', ' ', $module));

            $permission[] = [
                'name'        => 'Create ' . $title,
                'group'       => 'workshop',
                'slug'        => 'create-' . $module,
                'description' => 'Allow the user to create the ' . $title
            ];

            $permission[] = [
                'name'        => 'View ' . $title,
                'group'       => 'workshop',
                'slug'        => 'view-' . $module,
                'description' => 'Allow the user to view the ' . $title
            ];

            $permission[] = [
                'name'        => 'Edit ' . $title,
                'group'       => 'workshop',
                'slug'        => 'edit-' . $module,
                'description' => 'Allow the user to edit the ' . $title
            ];

            $permission[] = [
                'name'        => 'Delete ' . $title,
                'group'       => 'workshop',
                'slug'        => 'delete-' . $module,
                'description' => 'Allow the user to delete the ' . $title
            ];

            if (in_array($module, $hasApproval)) {
                $permission [] = [
                    'name'        => 'Approve ' . $title,
                    'group'       => 'workshop',
                    'slug'        => 'approve-' . $module,
                    'description' => 'Allow the user to approve the ' . $module
                ];
            }
        }

        return $permission;
    }

    /**
     * @param $transportModules
     * @param $permission
     * @param $hasApproval
     * @return array
     */
    private function mapTransportModules($transportModules, $permission, $hasApproval): array
    {
        foreach ($transportModules as $module) {
            $title = ucwords(str_replace('-', ' ', $module));

            $permission[] = [
                'name'        => 'Create ' . $title,
                'group'       => 'transport',
                'slug'        => 'create-' . $module,
                'description' => 'Allow the user to create the ' . $title
            ];

            $permission[] = [
                'name'        => 'View ' . $title,
                'group'       => 'transport',
                'slug'        => 'view-' . $module,
                'description' => 'Allow the user to view the ' . $title
            ];

            $permission[] = [
                'name'        => 'Edit ' . $title,
                'group'       => 'transport',
                'slug'        => 'edit-' . $module,
                'description' => 'Allow the user to edit the ' . $title
            ];

            $permission[] = [
                'name'        => 'Delete ' . $title,
                'group'       => 'transport',
                'slug'        => 'delete-' . $module,
                'description' => 'Allow the user to delete the ' . $title
            ];

            if (in_array($module, $hasApproval)) {
                $permission [] = [
                    'name'        => 'Approve ' . $title,
                    'group'       => 'transport',
                    'slug'        => 'approve-' . $module,
                    'description' => 'Allow the user to approve the ' . $module
                ];
            }
        }

        return $permission;
    }

    /**
     * @param $permission
     * @return array
     */
    private function mapDefaultModules($permission): array
    {
        $permission[] = [
            'name'        => 'Transport Module',
            'group'       => null,
            'slug'        => 'transport-module',
            'description' => 'Allow the user to access the transport module'
        ];

        $permission[] = [
            'name'        => 'Workshop Module',
            'group'       => null,
            'slug'        => 'workshop-module',
            'description' => 'Allow the user to access the workshop module'
        ];

        return $permission;
    }
}
