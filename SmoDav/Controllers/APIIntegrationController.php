<?php

namespace SmoDav\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SmoDav\Engine\PassportRepository;
use SmoDav\Models\APIIntegration;

class APIIntegrationController extends Controller
{
    public function index()
    {
        return view('workshop.integrations.index')->with('integrations', APIIntegration::all());
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $response = view('workshop.integrations.edit')
            ->with('integration', APIIntegration::findOrFail($id));

        if (session('success')) {
            $departments = PassportRepository::getPayrollDepartments();

            $response->with('departments', $departments)
                ->with('complete', true);
        };

        return $response;
    }

    public function update(Request $request, $id)
    {
        $integration = APIIntegration::findOrFail($id);
        $data = $request->all();
        $data['grant_type'] = 'authorization_code';

        return $integration->updateDetails($data);
    }

    public function finalize(Request $request, $id)
    {
        $integration = APIIntegration::findOrFail($id);

        return $integration->completeIntegration($request->all());
    }
}
