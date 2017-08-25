<?php

namespace SmoDav\Engine;

use App\Option;
use GuzzleHttp\Client;
use SmoDav\Models\APIIntegration;

class PassportRepository
{
    private static function getHeaders($token)
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ];
    }

    private static function getRequest($endpoint, $token)
    {
        $client = new Client;

        $response = $client->get($endpoint, [
            'headers' => self::getHeaders($token)
        ]);

        return \json_decode((string) $response->getBody());
    }

    private static function makeRequests($endpoint, APIIntegration $integration)
    {
        $result = [];
        $nextPage = $endpoint;
        try {
            \ini_set('max_execution_time', 300);
            while (! \is_null($nextPage)) {
                $response = self::getRequest($nextPage, $integration->access_token);
                $nextPage = $response->next_page_url;
                $result = \array_merge($result, $response->data);
            }
        } catch (\Exception $exception) {
            return false;
        }

        return $result;
    }

    public static function getPayrollDepartments()
    {
        $payroll = APIIntegration::payroll();
        $endpoint = \trim($payroll->endpoint, " \t\n\r\0\x0B/") . '/api/v1/department';

        return self::makeRequests($endpoint, $payroll);
    }

    public static function getPayrollEmployees()
    {
        $payroll = APIIntegration::payroll();

        $departments = Option::whereIn('option_key', [
            Option::PAYROLL_DEPARTMENT_DRIVER,
            Option::PAYROLL_WORKSHOP_DRIVER
        ])
            ->get(['option_value'])
            ->map(function ($option) {
                return $option->option_value;
            })
            ->toArray();

        $query = \http_build_query([
            'departments' => \implode(',', $departments)
        ]);

        $endpoint = \trim($payroll->endpoint, " \t\n\r\0\x0B/") . '/api/v1/employee?' . $query;

        return self::makeRequests($endpoint, $payroll);
    }
}
