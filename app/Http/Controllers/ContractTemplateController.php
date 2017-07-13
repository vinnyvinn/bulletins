<?php

namespace App\Http\Controllers;

use App\Client;
use App\Contract;
use App\ContractTemplate;
use App\Http\Helpers\Helpers;
use App\Http\Requests\ContractTemplateRequest;
use App\Option;
use App\Route;
use App\StockItem;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Response;
use SmoDav\Models\CargoClassification;
use SmoDav\Models\CargoType;
use SmoDav\Models\CarriagePoint;
use SmoDav\Support\Constants;
use function str_replace;
use Auth;

class ContractTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        $contracts = ContractTemplate::all(['id', 'name', 'created_at']);

        return Response::json([
            'contracts' => $contracts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function create()
    {
        $items = StockItem::where('ItemGroup', Helpers::get_option(Option::BILLABLE_GROUP))
            ->select(['StockLink', 'Description_1'])
            ->get();


        return Response::json([
            'routes' => Route::all(['id', 'source', 'destination', 'distance']),
            'clients' => Client::all(['DCLink', 'Name', 'Account']),
            'cargo_classifications' => CargoClassification::all(['id', 'name']),
            'cargo_types' => CargoType::all(['id', 'name', 'cargo_classification_id']),
            'carriage_points' => CarriagePoint::all(['id', 'name']),
            'stockItems' => $items,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(ContractTemplateRequest $request)
    {
        $data = $request->all();
        foreach ($data as $key => $value) {
            if ($value == 'null') {
                unset($data[$key]);
            }
        }

        $data['berthing_date'] = isset($data['berthing_date']) ?
            Carbon::parse(str_replace('/', '-', $data['berthing_date']))->format('Y-m-d') :
            null;
        $data['vessel_arrival_date'] = isset($data['vessel_arrival_date']) ?
            Carbon::parse(str_replace('/', '-', $data['vessel_arrival_date']))->format('Y-m-d') :
            null;

        $data['unloading_points'] = json_decode($data['unloading_points']);
        $data['shifts'] = json_decode($data['shifts']);

        $data['raw'] = json_encode($data);
        $data['shifts'] = json_encode($data['shifts']);
        $data['unloading_points'] = json_encode($data['unloading_points']);
        $data['user_id'] = Auth::id();


        unset($data['_token'], $data['_method']);

        ContractTemplate::create($data);

        return Response::json([
            'message' => 'Successfully created new contract template.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $items = StockItem::where('ItemGroup', Helpers::get_option(Option::BILLABLE_GROUP))
            ->select(['StockLink', 'Description_1'])
            ->get();

        $contract = ContractTemplate::findOrFail($id);

        $contract->raw = json_decode($contract->raw);

        return Response::json([
            'routes' => Route::all(['id', 'source', 'destination', 'distance']),
            'clients' => Client::all(['DCLink', 'Name', 'Account']),
            'cargo_classifications' => CargoClassification::all(['id', 'name']),
            'cargo_types' => CargoType::all(['id', 'name', 'cargo_classification_id']),
            'carriage_points' => CarriagePoint::all(['id', 'name']),
            'stockItems' => $items,
            'contract' => $contract
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        foreach ($data as $key => $value) {
            if ($value == 'null') {
                unset($data[$key]);
            }
        }
        $data['berthing_date'] = isset($data['berthing_date']) ?
            Carbon::parse(str_replace('/', '-', $data['berthing_date']))->format('Y-m-d') :
            null;
        $data['vessel_arrival_date'] = isset($data['vessel_arrival_date']) ?
            Carbon::parse(str_replace('/', '-', $data['vessel_arrival_date']))->format('Y-m-d') :
            null;

        $data['unloading_points'] = json_decode($data['unloading_points']);
        $data['shifts'] = json_decode($data['shifts']);

        $data['raw'] = json_encode($data);
        $data['shifts'] = json_encode($data['shifts']);
        $data['unloading_points'] = json_encode($data['unloading_points']);

        $contract = ContractTemplate::findOrFail($id);
        $contract->update($data);

        return Response::json([
            'message' => 'Successfully updated contract template.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        ContractTemplate::where('id', $id)->delete();

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully deleted contract template.',
            'contracts' => ContractTemplate::all(['id', 'name', 'created_at'])
        ]);
    }
}
