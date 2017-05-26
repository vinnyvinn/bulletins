<?php

namespace SmoDav\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        return view('workshop.integrations.edit')
            ->with('integration', APIIntegration::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $integration = APIIntegration::findOrFail($id);

        return $integration->updateDetails($request->all());
    }

    public function finalize(Request $request, $id)
    {
        $integration = APIIntegration::findOrFail($id);

        return $integration->completeIntegration($request->all());
    }
}
