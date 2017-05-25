<?php

namespace App\Http\Controllers;

use App\UDF;
use Illuminate\Http\Request;

class UDFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response()->json(UDF::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return response()->json(
            ['inputs'=>array('Short text','Image','Document','DateTime', 'Number', 'Checkbox', 'Long Text', 'Select','Radio'),
                'modules'=>UDF::MODULES
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $request->all();
        $slugcount = UDF::where('name',strtolower($request->name))->count();
        if (!is_null($request['value'])){
             $data['value'] = (string) explode(';', $request->value);
//            return $data['value'];
        }
        if ($slugcount > 0){
            $slug = convertString($request->name)."_".count($slugcount);
        }
        else{
            $slug = convertString($request->name);
        }

        addcolumn(UDF::TABLES[$request->module],$slug, $request->input_type);
        UDF::create(array_add($data,'slug',$slug));

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
        //
        return response()->json(UDF::findOrfail($id));
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
        return response()->json(UDF::findOrfail($id));
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
        $udf = UDF::findOrfail($id);
        if ($request->name == $udf->name){
            $udf->update($request->all());

           return response()->json(['message'=>'UDF Updated Successfully']);
        }

        $slugcount = UDF::where(strtolower('name'),strtolower($request->name))->count();

        if ($slugcount ==0){
            $slug = convertString($request->name);
        }
        else{
            $slug = convertString($request->name)."_".count($slugcount);
        }

        renamecolumn(UDF::TABLES[$request->section], $udf->slug, $slug);
        $udf->update(array_add($request->all(),'slug',$slug));

        return response()->json(['message'=>'UDF Updated Successfully']);
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
        $udf = UDF::findOrfail($id);
        deletecolumn(UDF::TABLES[$udf->section],$udf->name);
        $udf->delete();
        return response()->json(['message'=>'UDF deleted Successfully']);
    }

    public function moduleUdf($module){
      return response()->json(UDF::where('module', $module)->where('status', UDF::ACTIVE)->get()->toArray());
    }
}
