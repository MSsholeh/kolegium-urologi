<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    function __construct()
    {
        $this->title = 'Pengelolaan Admin';
        $this->route = 'admin.setting.admin';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'title' => $this->title,
            'breadcrumbs' => ['Pengaturan', $this->title => route($this->route.'.index')],
            'route' => $this->route
        ];

        return view($this->route.'.index', $data);
    }

    public function table(DataTables $datatables)
    {
        $query = Admin::select('*')
            ->with(['roles', 'university']);

        return $datatables->eloquent($query)
            ->addIndexColumn()
            ->editColumn('university', function($data) {
                return $data->university->name ?? '';
            })
            ->addColumn('role', function ($data) {
                $roles = Arr::pluck($data->roles()->get()->toArray(), 'name');
                return $roles ? implode(', ', $roles) : '';
            })
            ->addColumn('action', function ($data) {
                return '<a href="'.route($this->route.'.edit', [$data->id]).'" class="btn btn-label-brand btn-icon btn-sm action-edit"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Edit" data-boundary="window"><i class="fa fa-pencil-alt"></i></a>';
//                    <a href="'.route($this->route.'.destroy', [$data->id]).'" class="btn btn-label-danger btn-icon btn-sm action-delete"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Hapus" data-boundary="window"><i class="fa fa-trash"></i></a>';
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'title' => $this->title,
            'breadcrumbs' => ['Pengaturan', $this->title => route($this->route.'.index'), 'Create' => route($this->route.'.create')],
            'route' => $this->route,
            'roles' => Role::all()
        ];

        return view($this->route.'.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'university_id' => 'nullable'
        ]);

        $admin = new Admin;
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->university_id = $request->university_id;
        $admin->save();

        if ($request->roles) {
            $admin->syncRoles($request->roles);
        }

        if ($admin) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data gagal ditambahkan'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        if (!$admin) {
            return false;
        }

        $data = [
            'title' => $this->title,
            'breadcrumbs' => ['Pengaturan', $this->title => route($this->route.'.index'), 'Edit' => route($this->route.'.edit', $admin->id)],
            'route' => $this->route,
            'value' => $admin,
            'roles' => Role::all()
        ];

        return view($this->route.'.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(Request $request, Admin $admin)
    {
//        if ($request->password)
//            $admin->password = Hash::make($request->password);
//        $admin->name = $request->name;

        if ($request->has('university_id')) {
            $admin->university_id = $request->university_id;
            $admin->save();
        }

        if ($request->roles) {
            $admin->syncRoles($request->roles);
        }

        if ($admin) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditambahkan'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data gagal ditambahkan'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Admin $admin
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Admin $admin)
    {
        if ($admin) {

            $admin->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);

        } else {

            return response()->json([
                'success' => false,
                'message' => 'Data gagal dihapus!'
            ]);

        }
    }
}
