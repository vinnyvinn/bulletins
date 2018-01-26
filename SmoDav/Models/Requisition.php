<?php

namespace SmoDav\Models;

use App\Http\Helpers\Helpers;
use App\Option;
use App\StockItem;
use App\Truck;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use SmoDav\Support\Constants;

class Requisition extends SmoDavModel
{
    protected $fillable = [
        'job_card_id', 'user_id', 'status', 'mechanic_findings', 'raw_data'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lines()
    {
        return $this->hasMany(RequisitionLines::class);
    }

    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }

    public function scopeOwn($builder)
    {
        return $builder->where('user_id', Auth::id());
    }

    public function scopePending($builder)
    {
        return $builder->where('status', Constants::STATUS_APPROVED);
    }

    public function transferToSite($station_id=1)
    {
        /*$settings = Option::whereIn('option_key', [
            Option::WAREHOUSE_TO_TRANSFER,
            Option::WAREHOUSE_TRANSFER_BATCH
        ])->get();*/

        /*$batchId = $settings->where('option_key', Option::WAREHOUSE_TRANSFER_BATCH)->first();
        $warehouseId = $settings->where('option_key', Option::WAREHOUSE_TO_TRANSFER)->first();

        $batchId = $batchId ? $batchId->option_value : null;
        $warehouseId = $warehouseId ? $warehouseId->option_value : null;*/

        /*if (! $batchId || ! $warehouseId) {
            return false;
        }*/

        $transferstation = Station::where('id', $station_id)->first();


        $batchId = $transferstation ? $transferstation->warehouse_transfer_batch : null;
        $warehouseId = $transferstation ? $transferstation->warehouse_to_transfer : null;
        if (! $batchId || ! $warehouseId) {
            return false;
        }

        $jobCard = $this->jobCard;
        $project = Truck::where('id', $jobCard->vehicle_id)->first();
        $project = $project ? $project->project_id : 0;

        $toInsert = [];

        foreach ($this->lines as $index => $line) {
            $toInsert[] = [
                'iWhseTransferBatchID' => $batchId,
                'iStockID' => (int) $line->item_id,
                'iFromWhseID' => 1,
                'iToWhseID' => $warehouseId,
                'fQuantity' => (int) $line->issued_quantity,
                'cReference' => 'PR-' . $line->id . '-' . $index,
                'cDescription' => 'Parts Requisition',
                'iProjectID' => $project,
                'iUnitsOfMeasureID' => 0
            ];
        }

        return DB::table('_etblWhseTransferBatchLines')->insert($toInsert);
    }

    public function makeJournal($items, $station_id)
    {
        $transferstation = Station::where('id', $station_id)->first();


        $journalbatchid = $transferstation ? $transferstation->consumption_journal_batch : null;
        $warehouseId = $transferstation ? $transferstation->warehouse_to_transfer : null;
        if (! $journalbatchid || ! $warehouseId) {
            return false;
        }


        $result = StockItem::select(['StockLink', 'AveUCst', 'iUOMStockingUnitID'])
            ->whereIn('StockLink', \array_keys($items))
            ->get()
            ->keyBy('StockLink');

        $jobCard = $this->jobCard;
        $project = Vehicle::where('id', $jobCard->vehicle_id)->first();
        $project = $project ? $project->project_id : 0;

        foreach ($items as $itemId => $quantity) {
            $details = $result->get($itemId);

            DB::table('_etblInvJrBatchLines')->insert(
                [
                    'iInvJrBatchID' => $journalbatchid,//Helpers::get_option(Option::CONSUMPTION_JOURNAL_BATCH),
                    'iStockID' => $itemId,
                    'iWarehouseID' => $warehouseId,//Helpers::get_option(Option::WAREHOUSE_TO_TRANSFER),
                    'dTrDate' => Carbon::now(),
                    'iTrCodeID' => 37,
                    'iGLContraID' => 5,
                    'cReference' => 'PR-' . $this->id . '-' . $itemId . \time(),
                    'cDescription' => 'Consumption for PR-' . $this->id,
                    'fQtyIn' => 0,
                    'fQtyOut' => $quantity,
                    'fNewCost' => $details->AveUCst,
                    'iProjectID' => $project,
                    'cLineNotes' => '',
                    'iUnitsOfMeasureId' => $details->iUOMStockingUnitID
                ]);
        }
    }
}
