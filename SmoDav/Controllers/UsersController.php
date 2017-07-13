<?php

namespace SmoDav\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Permission;
use App\User;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;
use SmoDav\Models\Station;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return $this->getTableData();
        }

        return view('workshop.users.index');
    }

    private function getTableData()
    {
        $records = User::with(['stations' => function ($builder) {
            return $builder->select(['id', 'name']);
        }])
            ->where('id', '>', 1)
            ->get(['id', 'first_name', 'last_name', 'username', 'email']);


        return Datatables::of($records)
            ->addColumn('actions', function ($record) {
                return '
                <a href="' . route('workshop.users.edit', $record->id) .
                    '" class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></a>
                ';
            })
            ->editColumn('created_at', function ($record) {
                return Carbon::parse($record->created_at)->format('d F Y');
            })
            ->editColumn('stations', function ($record) {
                $stations = $record->stations->map(function ($station) {
                    return $station->name;
                })->flatten()->toArray();

                return implode(', ', $stations);
            })
            ->removeColumn('id')
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $stations = Station::all(['name', 'id']);
        $permissions = Permission::all(['name', 'group', 'slug']);

        return view('workshop.users.create')
            ->with('stations', $stations)
            ->with('permissions', $permissions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $data = $request->all();
        $data['permissions'] = "[]";
        if( isset($data['permission'])) {
          $data['permissions'] = json_encode(array_keys($request->get('permission')));
          unset($data['permission']);
        }
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        $user->stations()->sync($data['stations']);

        $route = 'workshop.users.index';

        if ($request->has('save_new')) {
            $route = 'workshop.users.create';
        }

        return redirect()->route($route)->with('success', 'Successfully added new system user.');
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $stations = Station::all(['name', 'id']);
        $permissions = Permission::all(['name', 'group', 'slug']);
        $user = User::with(['stations'])->findOrFail($id);
        $user->permissions = json_decode($user->permissions);
        $userStations = $user->stations->map(function ($station) {
            return $station->id;
        })->toArray();
        unset($user->stations);
        $user->stations = $userStations;

        return view('workshop.users.create')
            ->with('stations', $stations)
            ->with('permissions', $permissions)
            ->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->all();
        $data['permissions'] = json_encode(array_keys($request->get('permission')));
        unset($data['permission']);
        if ($request->get('password')) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);
        $user->stations()->sync($data['stations']);

        $route = 'workshop.users.index';

        if ($request->has('save_new')) {
            $route = 'workshop.users.create';
        }

        return redirect()->route($route)->with('success', 'Successfully updated system user.');
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
