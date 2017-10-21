<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadRequest;
use App\Route;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Response;
use SmoDav\Support\Excel;
use SmoDav\Models\MileageType;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        return Response::json([
            'routes' => Route::all()
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function create()
    {
        $source = Route::distinct('source')->get(['source'])->map(function ($item) {
            return $item->source;
        })->values()->toArray();
        $destination = Route::distinct('destination')->get(['destination'])->map(function ($item) {
            return $item->destination;
        })->values()->toArray();

        $routes = \array_merge($source, $destination);

        $routes = \array_values(\array_unique($routes));

        $mileageTypes = MileageType::all(['name', 'slug']);

        return Response::json([
            'locations' => $routes,
            'mileageTypes' => $mileageTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $exists = Route::where('source', $data['source'])->where('destination', $data['destination'])->exists();
        if ($exists) {
            return Response::json([
                'message' => 'Sorry, a similar route already exists.'
            ], 409);
        }

        \DB::transaction(function () use ($request, $data) {
            $route = Route::create($data);
            $altRoute = Route::create($data);
            $altRoute->source = $data['destination'];
            $altRoute->destination = $data['source'];
            unset(
                $data['source'], $data['destination'], $data['distance'], $data['fuel_required'],
                $data['allowance_amount'], $data['_method'], $data['_token']
            );

            foreach ($data as $key => $item) {
                $route->{$key} = $item;
                $altRoute->{$key} = $item;
                if ($request->hasFile($key)) {
                    $extension = $request->file($key)->getClientOriginalExtension();
                    $filename = \time() . '.' . $extension;
                    $request->file($key)->move(public_path('uploads'), $filename);
                    $route->{$key} = $filename;
                    $altRoute->{$key} = $filename;
                }
            }

            $route->save();
            $altRoute->save();
        });

        return Response::json([
            'message' => 'Successfully created new route.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Route $route
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show(Route $route)
    {
        $source = Route::distinct('source')->get(['source'])->map(function ($item) {
            return $item->source;
        })->values()->toArray();
        $destination = Route::distinct('destination')->get(['destination'])->map(function ($item) {
            return $item->destination;
        })->values()->toArray();

        $locations = \array_merge($source, $destination);
        $locations = \array_values(\array_unique($locations));

        $mileageTypes = MileageType::all(['name', 'slug']);

        return Response::json([
            'route' => $route,
            'locations' => $locations,
            'mileageTypes' => $mileageTypes
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Route               $route
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, Route $route)
    {
        $data = $request->all();
        $exists = Route::where('source', $data['source'])
            ->where('destination', $data['destination'])
            ->where('id', '<>', $route->id)
            ->exists();

        if ($exists) {
            return Response::json([
                'message' => 'Sorry, a similar route already exists.'
            ], 409);
        }

        \DB::transaction(function () use ($request, $data, $route) {
            $route->update($data);
            unset(
                $data['source'], $data['destination'], $data['distance'], $data['fuel_required'],
                $data['allowance_amount'], $data['_method'], $data['_token']
            );

            foreach ($data as $key => $item) {
                $route->{$key} = $item;
                if ($request->hasFile($key)) {
                    $extension = $request->file($key)->getClientOriginalExtension();
                    $filename = \time() . '.' . $extension;
                    $request->file($key)->move(public_path('uploads'), $filename);
                    $route->{$key} = $filename;
                }
            }
            $route->save();
        });

        return Response::json([
            'message' => 'Successfully updated route.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Route $route
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Route $route)
    {
        $route->delete();

        return Response::json([
            'status' => 'success',
            'routes' => Route::all(),
            'message' => 'Successfully deleted route.'
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

        $rows = Excel::prepare($file)
            ->usingHeaders([
                'source', 'destination', 'distance', 'fuel_required', 'allowance_amount'
            ])
            ->includeColumns([
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ])
            ->get();

        try {
            Route::insert($rows);
        } catch (Exception $ex) {
            echo $ex->getMessage();

            return Response::json([
                'status' => 'error',
                'message' => 'Please use the sample file format provided and fill all the required fields.'
            ]);
        }

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully imported routes.',
            'routes' => Route::all()
        ]);
    }
}
