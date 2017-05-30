<?php

namespace SmoDav\Models;

use App\Option;
use App\Truck;
use App\User;
use Auth;
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

    public function transferToSite()
    {
        $settings = Option::whereIn('option_key', [
            Option::WAREHOUSE_TO_TRANSFER,
            Option::WAREHOUSE_TRANSFER_BATCH
        ])->get();

        $batchId = $settings->where('option_key', Option::WAREHOUSE_TRANSFER_BATCH)->first();
        $warehouseId = $settings->where('option_key', Option::WAREHOUSE_TO_TRANSFER)->first();

        $batchId = $batchId ? $batchId->option_value : null;
        $warehouseId = $warehouseId ? $warehouseId->option_value : null;

        if (! $batchId || ! $warehouseId) {
            return false;
        }

        $jobCard = $this->jobCard;
        $project = Truck::where('id', $jobCard->vehicle_id)->first();
        $project = $project ? $project->project_id : 0;

        $toInsert = [];

        foreach ($this->lines as $line) {
            $toInsert[] = [
                'iWhseTransferBatchID' => $batchId,
                'iStockID' => (int) $line->item_id,
                'iFromWhseID' => 1,
                'iToWhseID' => $warehouseId,
                'fQuantity' => (int) $line->issued_quantity,
                'cReference' => "PR-" . $line->id,
                'cDescription' => 'Parts Requisition',
                'iProjectID' => $project,
                'iUnitsOfMeasureID' => 0
            ];
        }

        DB::table('_etblWhseTransferBatchLines')->insert($toInsert);
    }
}
