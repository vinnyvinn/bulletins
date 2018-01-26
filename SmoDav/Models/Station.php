<?php

namespace SmoDav\Models;

use App\EtblInvJrBatches;
use App\EtblWhseTransferBatches;
use App\User;
use App\WhseMst;
use Illuminate\Database\Eloquent\SoftDeletes;

class Station extends SmoDavModel
{
    use SoftDeletes;

    protected $fillable = ['name', 'location','warehouse_to_transfer','warehouse_transfer_batch','consumption_journal_batch'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    //warehouse

    //batches
    public function whstransferbatch()
    {
        return $this->belongsTo(EtblWhseTransferBatches::class,'warehouse_transfer_batch','idWhseTransferBatch');
    }

    //consumption batch
    public function consumptionbatch()
    {
        return $this->belongsTo(EtblInvJrBatches::class,'consumption_journal_batch','IDInvJrBatches');
    }

    public function whsemast()
    {
        return $this->belongsTo(WhseMst::class,'warehouse_to_transfer','WhseLink');
    }

}
