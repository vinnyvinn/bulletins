<?php
namespace App\traits;
use App\Employee;
use App\HrEmployeeDesignation;
use App\HrEmployeesModel;
use App\RequisitionHistory;
use Illuminate\Support\Facades\Auth;

/**
 * Created by PhpStorm.
 * User: HP
 * Date: 20/12/2017
 * Time: 15:12
 */
interface StatusInterface
{
    const STATUS_REQUESTED = 1;
    const STATUS_APPROVED = 2;
    const STATUS_ISSUED = 3;
    const STATUS_REJECTED = 4;
}

trait RequistionHistoryTrait{

    function requestReqHistory($requision_id){
     $this->saveReqHistoryStatus(StatusInterface::STATUS_REQUESTED,$requision_id);
    }

    function approveReqHistory($requsion_id){
        $this->saveReqHistoryStatus(StatusInterface::STATUS_APPROVED,$requsion_id);

    }

    function issueReqHistory($requsion_id){
      $this->saveReqHistoryStatus(StatusInterface::STATUS_ISSUED,$requsion_id);

    }

    function rejectReqHistory($requsion_id){
        $this->saveReqHistoryStatus(StatusInterface::STATUS_REJECTED,$requsion_id);

    }

    function saveReqHistoryStatus($status, $req_id){
        $reqhist = new RequisitionHistory();
        $reqhist->user_id = Auth::user()->id;
        $reqhist->status = $status;
        $reqhist->requistion_id = $req_id;
        $reqhist->save();
     }


}