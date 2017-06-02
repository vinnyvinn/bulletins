<?php

namespace App\Http\Controllers;

use App\Driver;
use App\Http\Requests\DriverRequest;
use App\Http\Requests\UploadRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Response;
use function sleep;
use SmoDav\Factory\DriverFactory;
use SmoDav\Support\Excel;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        return Response::json(DriverFactory::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $driver = Driver::create($request->all());

        foreach ($request->all() as $key => $item) {
            if ($key == '_token' || $key == '_method' || $key == 'updated_at' || $key == 'deleted_at') {
                continue;
            }

            $driver->{$key} = $item;

            if ($request->hasFile($key)) {
                $extension = $request->file($key)->getClientOriginalExtension();
                $filename = time().".".$extension;
                $request->file($key)->move(public_path('uploads'), $filename);
                $driver->{$key} = $filename;
            }
        }

        $driver->save();

        return Response::json([
            'message' => 'Successfully added new driver.',
            'driver' => $driver
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $driver = DriverFactory::findOrFail($id);

        return Response::json([
            'driver' => $driver
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(DriverRequest $request, $id)
    {
        $driver = DriverFactory::findOrFail($id);
        $driver->fill($request->all());

        foreach ($request->all() as $key => $item) {
            if ($key == '_token' || $key == '_method' || $key == 'updated_at' || $key == 'deleted_at') {
                continue;
            }

            $driver->{$key} = $item;

            if ($request->hasFile($key)) {
                $extension = $request->file($key)->getClientOriginalExtension();
                $filename = time().".".$extension;
                $request->file($key)->move(public_path('uploads'), $filename);
                $driver->{$key} = $filename;
            }
        }

        $driver->save();

        return Response::json([
            'message' => 'Successfully updated driver details.',
            'driver' => $driver
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Driver  $driver
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy(Driver $driver)
    {
        if ($driver->truck()->count()) {
            return Response::json([
                'status' => 'error',
                'message' => 'The driver ia assigned to a truck. Reassign the truck first.',
            ]);
        }

        $driver->delete();

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully deleted driver.',
            'drivers' => Driver::all()
        ]);
    }

    public function importDrivers(UploadRequest $request)
    {
        $file = $request->file('uploaded_file');
        if (! Excel::validateExcel($file)) {
            return Response::json([
                'status' => 'error',
                'message' => 'Please select a valid import file of type XLS or XLSX.'
            ]);
        }

        $existingDrivers = Driver::all(['identification_number'])->map(function ($driver) {
            return $driver->national_id;
        })->values()->toArray();

        $rows = Excel::prepare($file)
            ->usingHeaders([
                'first_name', 'identification_number', 'dl_number', 'mobile_phone'
            ])
            ->includeColumns([
                'last_name' => '',
                'identification_type' => 'National ID',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ])
            ->excludeRows('national_id', $existingDrivers)
            ->get();

        try {
            Driver::insert($rows);
        } catch (Exception $ex) {
            return Response::json([
                'status' => 'error',
                'message' => 'Please use the sample file format provided and fill all the required fields.'
            ]);
        }

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully imported drivers.',
            'drivers' => Driver::all()
        ]);
    }
}
