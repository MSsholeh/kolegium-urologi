<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Arr;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Set default value untuk title dan route
     */
    function __construct()
    {
        $this->title = 'Hak Akses';
        $this->route = 'admin.setting.role';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $data = [
            'title' => $this->title,
            'breadcrumbs' => ['Pengaturan', $this->title => route($this->route.'.index'), 'Create' => route($this->route.'.create')],
            'route' => $this->route,
            'permissions' => Permission::all()
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
        ]);

        $role = new Role;
        $role->name = $request->name;
        $role->guard_name = 'admin';
        $role->save();

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        if ($role) {
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
     * @param Role $role
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Role $role)
    {
        if (!$role) {
            return false;
        }

        $data = [
            'title' => $this->title,
            'breadcrumbs' => ['Pengaturan', $this->title => route($this->route.'.index'), 'Edit' => route($this->route.'.edit', $role->id)],
            'route' => $this->route,
            'value' => $role,
            'permissions' => Permission::all()
        ];

        return view($this->route.'.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $role->name = $request->name;
        $role->save();

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        if ($role) {
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
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Role $role)
    {
        if ($role) {
            $role->delete();
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

    /**
     * @param DataTables $datatables
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function table(Datatables $datatables)
    {
        $query = Role::select('*');

        return $datatables->eloquent($query)
            ->addIndexColumn()
            ->addColumn('permissions', function($data) {
                $roles = Arr::pluck($data->permissions()->get()->toArray(), 'name');
                return $roles ? join(', ', $roles) : '';
            })
            ->addColumn('action', function($data) {
                return '<a href="'.route($this->route.'.edit', [$data->id]).'" class="btn btn-label-brand btn-icon btn-sm action-edit"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Edit" data-boundary="window"><i class="fa fa-pencil-alt"></i></a>
                    <a href="'.route($this->route.'.destroy', [$data->id]).'" class="btn btn-label-danger btn-icon btn-sm action-delete"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Hapus" data-boundary="window"><i class="fa fa-trash"></i></a>';
            })
            ->make(true);
    }
}
