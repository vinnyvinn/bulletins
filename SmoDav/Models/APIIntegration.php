<?php

namespace SmoDav\Models;

use Carbon\Carbon;
use GuzzleHttp\Client;
use SmoDav\Engine\PassportRepository;

class APIIntegration extends SmoDavModel
{
    const PAYROLL = 'CloudWage - Payroll';

    protected $fillable = [
        'endpoint', 'grant_type', 'client_id', 'client_secret', 'redirect_uri', 'code', 'access_token', 'refresh_token',
        'expiry', 'name'
    ];

    public function scopePayroll($builder)
    {
        return $builder->where('name', self::PAYROLL)->first();
    }

    public function updateDetails($attributes)
    {
        if ($this->name == self::PAYROLL) {
            return $this->updatePayroll($attributes);
        }

        return redirect()->route('workshop.integrations.index');
    }

    private function updatePayroll($attributes)
    {
        $attributes['redirect_uri'] =
            substr($attributes['redirect_uri'], strlen(request()->getSchemeAndHttpHost()));
        if ($attributes['grant_type'] == 'personal_token') {
            $this->update([
                'endpoint' => $attributes['endpoint'],
                'grant_type' => $attributes['grant_type'],
                'access_token' => $attributes['access_token'],
                'redirect_uri' => $attributes['redirect_uri'],
            ]);

            return redirect()->route('workshop.integrations.index')
                ->with('success', 'Successfully updated integration.');
        }

        $this->update([
            'endpoint' => $attributes['endpoint'],
            'grant_type' => $attributes['grant_type'],
            'client_id' => $attributes['client_id'],
            'client_secret' => $attributes['client_secret'],
            'redirect_uri' => $attributes['redirect_uri']
        ]);

        return $this->authorizeRequest();
    }

    private function authorizeRequest()
    {
        $scopes = [
            'read-departments', 'read-employee-assignment', 'read-employee-contracts',
            'read-employees'
        ];

        $query = http_build_query([
            'client_id' => $this->client_id,
            'redirect_uri' => request()->getSchemeAndHttpHost() . $this->redirect_uri,
            'response_type' => 'code',
            'scope' => implode(' ', $scopes),
        ]);

        return redirect(rtrim($this->endpoint, '/') . '/oauth/authorize?' .$query);
    }

    public function completeIntegration($attributes)
    {
        if (array_key_exists('code', $attributes)) {
            return $this->requestAccessToken($attributes);
        }

        return redirect()->route('workshop.integrations.edit', $this->id)
            ->with('error', 'Unable to complete the integration. Please try again.');
    }

    private function requestAccessToken($attributes)
    {
        $this->update($attributes);

        $http = new Client;

        try {
            $response = $http->post(rtrim($this->endpoint, '/') .'/oauth/token', [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'client_id' => $this->client_id,
                    'client_secret' => $this->client_secret,
                    'redirect_uri' => request()->getSchemeAndHttpHost() . $this->redirect_uri,
                    'code' => $this->code,
                ],
            ]);

            $tokens = json_decode((string) $response->getBody(), true);

            $this->update([
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
                'expiry' => Carbon::now()->addSeconds($tokens['expires_in'])
            ]);
        } catch (\Exception $ex) {
            return redirect()->route('workshop.integrations.edit', $this->id)
                ->with('error', 'Unable to complete the integration. Please try again.');
        }

        return redirect()->route('workshop.integrations.edit', $this->id)
            ->with('success', 'Successfully completed integration.');
    }
}
