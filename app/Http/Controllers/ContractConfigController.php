<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\ContractConfig;
use Response;
use DB;

class ContractConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Response::json([
          'contract_settings' => ContractConfig::orderBy('created_at', 'asc')->get()
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $fields = $request->all();

      $contract_setting = new ContractConfig;

      foreach($fields as $key => $field) {
        if(!($key == '_method' || $key == '_token')) {
          $contract_setting->$key = $field;
        }
      }
      $contract_setting->save();

      return Response::json([
        'status' => 'success',
        'message' => 'Settings Successfully saved'
      ]);
    }

    public function addConfigField(Request $request)
    {
        $data = $request->all();

        $schema = DB::getDoctrineSchemaManager();
        $fields = $schema->listTableColumns('contract_configs');
        $slugcount = 0;
        foreach($fields as $field) {
          if($field == $data['name']) {
            $slugcount = $slugcount + 1;
          }
        }
        $slug = convertString($data['name']);

        if ($slugcount > 0){
            $slug .= "_".$slugcount;
        }

        $data['name'] = $slug;

        Schema::table('contract_configs', function(Blueprint $table) use($data) {
            $table->integer($data['name'])->nullable();
        });

        return response()->json([
            'message' => 'Successfully added new field.'
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
        return Response::json([
          'status' => 'success',
          'message' => 'successful',
          'setting' => ContractConfig::where('contract_id', $id)->first()
        ]);
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
      $fields = $request->all();

      $contract_setting = ContractConfig::findOrFail($id);

      foreach($fields as $key => $field) {
        if(!($key == '_method' || $key == '_token')) {
          $contract_setting->$key = $field;
        }
      }
      $contract_setting->save();

      return Response::json([
        'status' => 'success',
        'message' => 'Settings Successfully saved'
      ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteField($field)
    {
      Schema::table('contract_configs', function($table) {
             $table->dropColumn($field);
      });
      return Response::json([
        'message' => 'Field removed successfully'
      ]);
    }

    public function getTableFields()
    {

      $schema = DB::getDoctrineSchemaManager();
      $fields = $schema->listTableColumns('contract_configs');

      return Response::json([
        'fields' => $fields
      ]);
    }

    public function checkConfigPresence($id)
    {

    }
}
