<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Daster;
use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\RequirementGraduation;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\Presets\React;
use Yajra\DataTables\DataTables;

class RequirementGraduationController extends Controller
{
    private $title;
    private $route;

    public function __construct()
    {
        $this->title = 'Persyaratan Pendaftaran Ujian Nasional';
        $this->route = 'admin.requirement-graduation';
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $data = [
            'title' => $this->title,
            'breadcrumbs' => [$this->title => route($this->route.'.index')],
            'route' => $this->route
        ];

        return view($this->route.'.index', $data);
    }

    /**
     * Show list data for Datatables.
     *
     * @param DataTables $datatables
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function table(DataTables $datatables)
    {
        $query = RequirementGraduation::select('*')->with('admin', 'university', 'period');

        $admin = Auth::user();
        if ($admin->isAdminUniversity()) {
           $query->where('university_id', $admin->university_id);
        }

        return $datatables->eloquent($query)
            ->addIndexColumn()
            ->addColumn('university', function ($data) {
                return $data->university->name ?? '';
            })
            ->addColumn('period', function ($data) {
                return $data->period->name;
            })
            ->editColumn('created_at', function($data) {
                return Daster::tanggal($data->created_at, 1, true);
            })
            ->addColumn('admin', function($data) {
                return $data->admin->name;
            })
            ->addColumn('action', function ($data) {
                $active = '<a href="' . route($this->route . '.change', [$data->id]) . '" class="btn btn-label-' . ($data->status === 'Active' ? 'success' : 'dark') . ' btn-icon btn-sm action-confirm" data-confirm="ubah status" data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Set ' . ($data->status === 'Active' ? 'Tidak' : '') . ' Aktif" data-boundary="window"><i class="fa fa-check-circle"></i></a>';
                $edit = ' <a href="' . route($this->route . '.edit', [$data->id]) . '" class="btn btn-label-brand btn-icon btn-sm action-edit"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Edit" data-boundary="window"><i class="fa fa-pencil-alt"></i></a>';
                $destroy = ' <a href="' . route($this->route . '.destroy', [$data->id]) . '" class="btn btn-label-danger btn-icon btn-sm action-delete"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Hapus" data-boundary="window"><i class="fa fa-trash"></i></a>';
                if (auth()->user()->can('Persyaratan Lulus: Lihat, Tambah, Ubah, Hapus')) {
                    return $active.$edit.(auth()->user()->can('Persyaratan Lulus: Lihat Semua') || $data->status === 'Inactive' ? $destroy : '');
                }
            })
            ->rawColumns(['action'])
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
            'breadcrumbs' => [$this->title => route($this->route.'.index')],
            'route' => $this->route
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
            'university_id' => 'required',
            'period_id' => 'required',
            'requirements' => 'required',
            'note' => 'required'
        ]);

        $requirement = new RequirementGraduation;
        $requirement->university_id = $request->university_id;
        $requirement->admin_id = Auth::user()->id;
        $requirement->note = $request->note;
        $requirement->period_id = $request->period_id;
        $requirement->save();

        $requirements = [];
        foreach ($request->requirements as $require) {
            $requirements[] = [
                'name' => $require['name'],
                'type' => $require['type'],
                'required' => @$require['required'] ? true : false
            ];
        }

        $requirement->items()->createMany($requirements);

        return response()->json(['success' => true, 'message' => 'Data berhasil disimpan.']);

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
     * @param RequirementGraduation $requirement
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $data = [
            'title' => $this->title,
            'breadcrumbs' => [$this->title => route($this->route.'.index')],
            'route' => $this->route,
            'requirement' => RequirementGraduation::where('id',$id)->with('admin', 'period')->first(),
            'types' => ['Text', 'Checkbox', 'File']
        ];

        return view($this->route.'.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param RequirementGraduation $requirement
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'period_id' => 'required',
            'requirements' => 'required'
        ]);

        $requirement=RequirementGraduation::whereid($request->id)->firstOrFail();

        if ($request->has('university_id')) {
            $requirement->university_id = $request->university_id;
        }
        $requirement->note = $request->note;
        $requirement->save();

        $exist = $requirement->items;

        $requirements = [];
        $updated = [];
        foreach ($request->requirements as $require) {
            $store = [
                'name' => $require['name'],
                'type' => $require['type'],
                'required' => @$require['required'] ? true : false
            ];

            if (@$require['id']) {
                $requirement->items()
                    ->where('id', $require['id'])
                    ->update($store);

                $updated[] = $require['id'];
            } else {
                $requirements[] = $store;
            }
        }

        if (count($requirements) > 0) {
            $requirement->items()->createMany($requirements);
        }

        $diff = $exist->pluck('id')->diff($updated);

        $requirement->items()->whereIn('id', $diff)->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil disimpan.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $requirement=RequirementGraduation::whereid($id)->firstOrFail();
        $requirement->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }

    public function change($id)
    {
        $requirement=RequirementGraduation::whereid($id)->firstOrFail();
        if ($requirement->status === 'Active') {
            $requirement->status = 'Inactive';
        } else {
            RequirementGraduation::where(['university_id' => $requirement->university->id, 'period_id' => $requirement->period_id])->update(['status' => 'Inactive']);
            $requirement->status = 'Active';
        }
        $requirement->save();

        return response()->json(['success' => true, 'message' => 'Status berhasil diubah']);
    }
}
