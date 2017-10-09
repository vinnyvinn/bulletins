<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SmoDav\Models\Checkpoint;

class CheckpointController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('masters.checkpoints.index')->with('checkpoints', Checkpoint::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('masters.checkpoints.create');
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
            'name' => 'required|unique:checkpoints'
        ]);

        Checkpoint::create($request->all());

        if ($request->has('save_new')) {
            return redirect()->back()->with('success', 'Successfully added new checkpoint');
        }

        return redirect()->route('workshop.checkpoint.index')->with('success', 'Successfully added new checkpoint');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Checkpoint  $checkpoint
     * @return \Illuminate\Http\Response
     */
    public function edit(Checkpoint $checkpoint)
    {
        return view('masters.checkpoints.edit', compact('checkpoint'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Checkpoint  $checkpoint
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Checkpoint $checkpoint)
    {
        $this->validate($request, [
            'name' => 'required|unique:checkpoints,name,' . $checkpoint->id
        ]);

        $checkpoint->update($request->all());

        return redirect()->route('workshop.checkpoint.index')->with('success', 'Successfully updated checkpoint');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Checkpoint  $checkpoint
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checkpoint $checkpoint)
    {
        //
    }
}
