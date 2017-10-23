<?php

namespace App\Http\Controllers;

use App\BreakdownArea;
use Illuminate\Http\Request;

class BreakdownAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('masters.areas.index')->with('areas', BreakdownArea::all(['id', 'name']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('masters.areas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:breakdown_areas'
        ]);

        BreakdownArea::create($request->all());

        if ($request->has('save_new')) {
            return redirect()->back()->with('success', 'Successfully added new area');
        }

        return redirect()->route('workshop.area.index')->with('success', 'Successfully added new area');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BreakdownArea  $area
     * @return \Illuminate\Http\Response
     */
    public function edit(BreakdownArea $area)
    {
        return view('masters.areas.edit')->with('area', $area);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BreakdownArea  $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BreakdownArea $area)
    {
        $this->validate($request, [
            'name' => 'required|unique:breakdown_areas,name,' . $area->id
        ]);

        $area->update($request->all());

        return redirect()->route('workshop.area.index')->with('success', 'Successfully updated area');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BreakdownArea  $area
     * @return \Illuminate\Http\Response
     */
    public function destroy(BreakdownArea $area)
    {
        //
    }
}
