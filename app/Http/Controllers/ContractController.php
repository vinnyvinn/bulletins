<?php

namespace App\Http\Controllers;

use App\Client;
use App\Contract;
use App\Route;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Response;
use function str_replace;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        return Response::json([
            'contracts' => Contract::with(['client'])->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function create()
    {
        return Response::json([
            'routes' => Route::all(['id', 'source', 'destination', 'distance']),
            'clients' => Client::all(['DCLink', 'Name', 'Account']),
            'stockItems' => DB::table('StkItem')->select(['StockLink', 'Description_1'])->get()
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
        $data = $request->all();
        $data['start_date'] = Carbon::parse(str_replace('/', '-', $data['start_date']))->format('Y-m-d');
        $data['end_date'] = Carbon::parse(str_replace('/', '-', $data['end_date']))->format('Y-m-d');
        $contract = Contract::create($data);
        foreach ($request->all() as $key => $item) {
            $contract->{$key} = $item;
            if ($request->hasFile($key)) {
                $extension = $request->file($key)->getClientOriginalExtension();
                $filename = time().".".$extension;
                $request->file($key)->move(public_path('uploads'), $filename);
                $contract->{$key} = $filename;
            }
        }

        $contract->save();

        return Response::json([
            'message' => 'Successfully created new contract ending on ' .
                Carbon::parse(str_replace('/', '-', $data['end_date']))->format('l dS F Y')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contract  $contract
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show(Contract $contract)
    {
        return Response::json([
            'contract' => $contract
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contract  $contract
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, Contract $contract)
    {
        $data = $request->all();

        foreach ($request->all() as $key => $item) {
            $contract->{$key} = $item;
            if ($request->hasFile($key)) {
                $extension = $request->file($key)->getClientOriginalExtension();
                $filename = time().".".$extension;
                $request->file($key)->move(public_path('uploads'), $filename);
                $contract->{$key} = $filename;
            }
        }

        $contract->start_date = Carbon::parse(str_replace('/', '-', $data['start_date']))->format('Y-m-d');
        $contract->end_date = Carbon::parse(str_replace('/', '-', $data['end_date']))->format('Y-m-d');
        $contract->save();

        return Response::json([
            'message' => 'Successfully updated contract.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contract $contract)
    {
        //
    }
}
