<?php

namespace App\Http\Controllers;

use App\HrEmployeesModel;
use Illuminate\Http\Request;
use App\EmployeeCategory;
use Response;

class EmployeeCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return Response::json([
          'employee_categories' => EmployeeCategory::orderBy('category', 'asc')->get(),
            'hremployees'=>HrEmployeesModel::get()
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
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $employeecategory = EmployeeCategory::create($data);

        return Response::json([
          'status' => 'success',
          'message' => 'Employee category successfully added'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        $employee_category = EmployeeCategory::findOrFail($id);
        $employee_category->category = $data['category'];

        $employee_category->update();

        return Response([
          'status' => 'success',
          'message' => 'Category successfully edited'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = EmployeeCategory::findOrFail($id)->delete();

        return Response::json([
          'status' => 'success',
          'message' => 'Category deleted successfully'
        ]);
    }
}
