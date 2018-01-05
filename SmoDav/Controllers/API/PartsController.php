<?php

namespace SmoDav\Controllers\API;

use App\ConsumptionLine;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Helpers;
use App\IssueLine;
use App\Option;
use App\SAGEUDF;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Response;
use SmoDav\Factory\VehicleFactory;
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
        $requisitions = Requisition::when(! \Auth::user()->has('approve-requisition'), function ($builder) {
            return $builder->own();
        })
            ->with(['jobCard' => function ($builder) {
                return $builder->select(['id', 'vehicle_number']);
            }])
            ->when(! \request('status'), function ($builder) {
                return $builder->where('status', Constants::STATUS_PENDING);
            })
            ->when(\request('status'), function ($builder) {
                return $builder->where('status', \request('status'));
            })
            ->get([
                'id', 'job_card_id', 'created_at', 'status'
            ]);

        return Response::json([
            'requisitions' => $requisitions
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
            "(SELECT option_value FROM options WHERE option_key = '" . SAGEUDF::MODEL_UDF . "')");

        $makeColumn = DB::select('SELECT cFieldName FROM _rtblUserDict WHERE idUserDict = ' .
            "(SELECT option_value FROM options WHERE option_key = '" . Make::UDF . "')");

        if (\count($modelColumn)) {
            $modelColumn = $modelColumn[0]->cFieldName;
        }

        if (\count($makeColumn)) {
            $makeColumn = $makeColumn[0]->cFieldName;
        }

        $products = DB::table('StkItem')->where('ItemGroup', Helpers::get_option(Option::OPTION_ITEM_GROUP))
            ->select([
                'StockLink', 'Description_1', "{$modelColumn} as product_model", "{$makeColumn} as product_make"
            ])
            ->get();

        $jobCards = JobCard::select(['id', 'raw_data', 'vehicle_number'])
            ->open()
            ->get();

        return Response::json([
            'parts' => $products,
            'cards' => $jobCards,
            'trucks'=>VehicleFactory::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['raw_data'] = \json_encode($data);
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
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $requisition = Requisition::with(['user'])->findOrFail($id);
        $requisition->raw_data = \json_decode($requisition->raw_data);

        return Response::json([
            'requisition' => $requisition,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $requisition = Requisition::find($id);

        $data = $request->all();
        $data['raw_data'] = \json_encode($data);
        $data['user_id'] = Auth::id();

        DB::transaction(function () use ($data, $requisition) {
            $requisition->update($data);
            RequisitionLines::where('requisition_id', $requisition->id)->delete();

            foreach ($data['lines'] as $line) {
                $line['requisition_id'] = $requisition->id;
                if ($line['approved_quantity'] == 'Pending') {
                    unset($line['approved_quantity']);
                }
                if ($line['issued_quantity'] == 'Pending') {
                    unset($line['issued_quantity']);
                }

                RequisitionLines::create($line);
            }
        });

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully updated requisition.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $requisition = Requisition::find($id);

        if ($requisition->status != 'Pending Approval') {
            return Response::json([
                'status' => 'success',
                'message' => 'You cannot delete a processed requisition.'
            ]);
        }

        $requisition->delete();

        return Response::json([
            'status' => 'success',
            'message' => 'Successfully deleted requisition.'
        ]);
    }

    public function approve(Request $request, $id)
    {
        $requisition = DB::transaction(function () use ($request, $id) {
            $lines = collect($request->get('lines'))->keyBy('item_id');
            $requisition = Requisition::with(['lines'])->where('id', $id)->first();
            $data = $request->all();
            $data['lines'] = [];
            $toIssue = [];
            $issued = true;

            foreach ($requisition->lines as $line) {
                $itemLine = $lines->get($line->item_id);
                $fields = [
                    'approved_quantity' => $itemLine['approved_quantity']
                ];

                if ($requisition->status == Constants::STATUS_APPROVED) {
                    if ((int) $itemLine['issued_quantity']) {
                        $toIssue [] = [
                            'requisition_line_id' => $line->item_id,
                            'issued' => $itemLine['issued_quantity'],
                            'user_id' => \Auth::id(),
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];
                    }

                    $itemLine['issued_quantity'] += $line->issued_quantity;
                    $fields = [
                        'issued_quantity' => $itemLine['issued_quantity']
                    ];
                }

                $itemLine['old_consumed_quantity'] = 0;
                $data['lines'][] = $itemLine;
                $line->update($fields);
                if ((int) $line->approved_quantity > $line->issued_quantity) {
                    $issued = false;
                }
            }

            if (count($toIssue) && $requisition->status == Constants::STATUS_APPROVED) {
                IssueLine::insert($toIssue);
            }

            $status = $requisition->status == Constants::STATUS_PENDING ?
                Constants::STATUS_APPROVED :
                ($issued ? Constants::STATUS_ISSUED : Constants::STATUS_APPROVED);

            $requisition->update([
                'status' => $status,
                'raw_data' => \json_encode($data)
            ]);

            if ($requisition->status == Constants::STATUS_ISSUED) {
                $requisition->transferToSite();
            }

            return $requisition;
        });

        $requisition = Requisition::with(['jobCard'])->orderBy('id', 'desc')->find($requisition->id);
        $requisition->raw_data = json_decode($requisition->raw_data);

        $printout = view('printouts.requisition')
            ->with('requisition', $requisition)
            ->render();

        return Response::json([
            'success' => 'true',
            'message' => 'Successfully updated requisition.',
            'printout' => $printout
        ]);
    }

    public function consume(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            $lines = collect($request->get('lines'))->keyBy('item_id');
            $requisition = Requisition::with(['lines'])->where('id', $id)->first();
            $data = $request->all();
            $data['lines'] = [];
            $consumed = [];
            $toInsert = [];
            $fullyConsumed = true;
            foreach ($requisition->lines as $line) {
                $itemLine = $lines->get($line->item_id);
                $totalConsumed = $line->consumed_quantity + $itemLine['consumed_quantity'];
                
                if ($totalConsumed > $line->issued_quantity) {
                    $itemLine['consumed_quantity'] = $line->issued_quantity - $line->consumed_quantity;
                }

                $fields = [
                    'consumed_quantity' => $line->consumed_quantity + $itemLine['consumed_quantity']
                ];

                if (\intval($itemLine['consumed_quantity'])) {
                    $consumed[$itemLine['item_id']] = $itemLine['consumed_quantity'];
                    $itemLine['old_consumed_quantity'] = $fields['consumed_quantity'];
                    $itemLine['consumed_quantity'] = 0;
                    $toInsert [] = [
                        'requisition_line_id' => $line->item_id,
                        'consumed' => $itemLine['old_consumed_quantity'],
                        'user_id' => \Auth::id(),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];

                    $line->update($fields);
                }

                if ($line->issued_quantity > $line->consumed_quantity) {
                    $fullyConsumed = false;
                }

                $data['lines'][] = $itemLine;
            }

            if (count($toInsert)) {
                ConsumptionLine::insert($toInsert);
            }

            $requisition->makeJournal($consumed);

            $requisition->update([
                'status' => $fullyConsumed ? Constants::STATUS_CLOSED : Constants::STATUS_ISSUED,
                'raw_data' => \json_encode($data)
            ]);
        });

        return Response::json([
            'success' => 'true',
            'message' => 'Successfully updated requisition.'
        ]);
    }

    public function disapprove($id)
    {
        Requisition::where('id', $id)->update([
            'status' => Constants::STATUS_DECLINED
        ]);

        return Response::json([
            'success' => 'true',
            'message' => 'Successfully declined requisition.'
        ]);
    }
}
