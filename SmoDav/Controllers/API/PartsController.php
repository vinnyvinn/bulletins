<?php

namespace SmoDav\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Helpers;
use App\Option;
use App\SAGEUDF;
use Auth;
use DB;
use Illuminate\Http\Request;
use Response;
use SmoDav\Models\JobCard;
use SmoDav\Models\Make;
use SmoDav\Models\Requisition;
use SmoDav\Models\RequisitionLines;
use SmoDav\Support\Constants;

class PartsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return Response::json([

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        $modelColumn = DB::select('SELECT cFieldName FROM _rtblUserDict WHERE idUserDict = ' .
            "(SELECT option_value FROM options WHERE option_key = '". SAGEUDF::MODEL_UDF . "')");

        $makeColumn = DB::select('SELECT cFieldName FROM _rtblUserDict WHERE idUserDict = ' .
            "(SELECT option_value FROM options WHERE option_key = '". Make::UDF . "')");

        if (count($modelColumn)) {
            $modelColumn = $modelColumn[0]->cFieldName;
        }

        if (count($makeColumn)) {
            $makeColumn = $makeColumn[0]->cFieldName;
        }

        $products = DB::table('StkItem')->where('ItemGroup', Helpers::get_option(Option::OPTION_ITEM_GROUP))
            ->select([
                'StockLink', 'Description_1', "{$modelColumn} as product_model", "{$makeColumn} as product_make"
            ])
            ->get();

        $jobCards = JobCard::select(['id', 'raw_data', 'vehicle_number'])
            ->own()
            ->open()
            ->get();

        return Response::json([
            'parts' => $products,
            'cards' => $jobCards,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['raw_data'] = json_encode($data);
        $data['status'] = Constants::STATUS_PENDING;
        $data['user_id'] = Auth::id();

        DB::transaction(function () use ($data) {
            $requisition = Requisition::create($data);

            foreach ($data['lines'] as $line) {
                $line['requisition_id'] = $requisition->id;
                unset($line['approved_quantity'], $line['issued_quantity']);

                RequisitionLines::create($line);
            }
        });

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully requested for parts.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
