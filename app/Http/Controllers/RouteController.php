<?php

namespace App\Http\Controllers;

use App\Route;
use Illuminate\Http\Request;
use Response;
use function sleep;

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
}
