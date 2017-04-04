<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadRequest;
use App\Route;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Response;
use function sleep;
use SmoDav\Support\Excel;

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Route::create($request->all());

        return Response::json([
            'message' => 'Successfully created new route.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Route  $route
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show(Route $route)
    {
        return Response::json([
            'route' => $route
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Route  $route
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, Route $route)
    {
        $route->update($request->all());

        return Response::json([
            'message' => 'Successfully updated route.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Route  $route
     * @return \Illuminate\Http\Response
     */
    public function destroy(Route $route)
    {
        //
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
            ->whenNull(Excel::EXCLUDE_ROW)
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
