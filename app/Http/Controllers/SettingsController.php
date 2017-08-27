<?php

namespace App\Http\Controllers;

use App\Option;
use App\SAGEUDF;
use DB;
use Illuminate\Http\Request;

use SmoDav\Models\Make;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $pageTitle = 'Settings';
        $stkItemColumns = SAGEUDF::where('cTableName', 'LIKE', 'stkitem')
            ->where('iFieldType', 5)
            ->get([
                'idUserDict', 'cFieldName', 'cFieldDescription'
            ]);

        $warehouseTransferBatches = DB::table('_etblWhseTransferBatches')
            ->select([
                'idWhseTransferBatch', 'cBatchNo', 'cBatchDescription'
            ])
            ->get();

        $journalBatches = DB::table('_etblInvJrBatches')
            ->select([
                'IDInvJrBatches', 'cInvJrNumber', 'cInvJrDescription'
            ])
            ->get();

        $warehouses = DB::table('WhseMst')
            ->where('WhseLink', '<>', 1)
            ->select([
                'WhseLink', 'Code', 'Name'
            ])
            ->get();

        $itemGroups = DB::table('GrpTbl')->select(['idGrpTbl', 'StGroup'])->get();

        return view('admin.settings_common')
            ->with('journalBatches', $journalBatches)
            ->with('warehouseBatches', $warehouseTransferBatches)
            ->with('warehouses', $warehouses)
            ->with('stkItemGroups', $itemGroups)
            ->with('stkItemColumns', $stkItemColumns)
            ->with('pageTitle', $pageTitle);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $inputs = array_except($request->input(), ['_token']);

        foreach ($inputs as $key => $value) {
            $option = Option::firstOrCreate(['option_key' => $key]);
            $option -> option_value = $value;
            $option->save();
        }

        Make::updateFromSAGE();

        return redirect()->back()->with('success', 'Settings updated');
    }
}
