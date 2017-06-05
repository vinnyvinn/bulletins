<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrailerRequest;
use App\Http\Requests\UploadRequest;
use App\Trailer;
use Carbon\Carbon;
use function collect;
use Exception;
use Illuminate\Http\Request;
use Response;
use SmoDav\Support\Excel;

class TrailerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        return Response::json([
            'status' => 'success',
            'trailers' => Trailer::with('truck')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TrailerRequest  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(TrailerRequest $request)
    {
        $data = $request->all();
        foreach ($request->all() as $key => $item) {
            $data[$key] = $item;
            if ($request->hasFile($key)) {
                $extension = $request->file($key)->getClientOriginalExtension();
                $filename = time().".".$extension;
                $request->file($key)->move(public_path('uploads'), $filename);
                $data[$key] = $filename;
            }
        }

        unset($data['_method'], $data['_token']);

        Trailer::create($data);

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully added new trailer.',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Trailer  $trailer
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show(Trailer $trailer)
    {
        return Response::json([
            'status' => 'success',
            'trailer' => $trailer
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Trailer  $trailer
     * @return \Illuminate\Http\Response
     */
    public function edit(Trailer $trailer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Trailer  $trailer
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(TrailerRequest $request, Trailer $trailer)
    {
        $data = $request->all();
        foreach ($request->all() as $key => $item) {
            $data[$key] = $item;

            if ($request->hasFile($key)) {
                $extension = $request->file($key)->getClientOriginalExtension();
                $filename = time().".".$extension;
                $request->file($key)->move(public_path('uploads'), $filename);
                $data[$key] = $filename;
            }
        }
        unset($data['_method'], $data['_token']);

        $trailer->update($data);

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully updated trailer details.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Trailer  $trailer
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function destroy(Trailer $trailer)
    {
        $trailer->delete();

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully deleted trailer.',
            'trailers' => Trailer::with(['truck'])->get()
        ]);
    }

    public function import(UploadRequest $request)
    {
        $file = $request->file('uploaded_file');
        if (! Excel::validateExcel($file)) {
            return Response::json([
                'status' => 'error',
                'message' => 'Please select a valid import file of type XLS or XLSX.'
            ]);
        }

        $trailers = Trailer::all(['trailer_number'])->map(function ($truck) {
            return $truck->trailer_number;
        })->toArray();

        $rows = Excel::prepare($file)
            ->usingHeaders([
                'trailer_number', 'make', 'type'
            ])
            ->whenNull(Excel::EXCLUDE_ROW)
            ->includeColumns([
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ])
            ->excludeRows('plate_number', $trailers)
            ->get();

        $rows = collect($rows)->keyBy('trailer_number')->values()->toArray();

        try {
            Trailer::insert($rows);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            return Response::json([
                'status' => 'error',
                'message' => 'Please use the sample file format provided and fill all the required fields.'
            ]);
        }

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully imported trucks.',
            'trailers' => Trailer::all()
        ]);
    }

}
