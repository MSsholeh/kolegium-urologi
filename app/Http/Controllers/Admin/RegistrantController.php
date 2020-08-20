<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Daster;
use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\Registrant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use function foo\func;

class RegistrantController extends Controller
{
    private $title;
    private $route;

    public function __construct()
    {
        $this->title = 'Pendaftar';
        $this->route = 'admin.registrant';
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
        $query = Registrant::select('*', 'registrants.status as status_registrant', 'registrants.id as primary')
            ->with('user', 'university', 'requirement.period');

        $admin = Auth::user();
        if ($admin->isAdminUniversity()) {
            $query->where('registrants.university_id', $admin->university_id);
        }

        return $datatables->eloquent($query)
            ->addIndexColumn()
            ->addColumn('name', function ($data) {
                return $data->user->name;
            })
            ->filterColumn('name', function($query, $keyword) {
                return $query->whereHas('user', function($q) use($keyword) {
                    $q->where(DB::raw('LOWER(name)'), 'like', '%'.strtolower($keyword).'%');
                });
            })
            ->addColumn('nik', function ($data) {
                return $data->user->nik;
            })
            ->filterColumn('nik', function($query, $keyword) {
                return $query->whereHas('user', function($q) use($keyword) {
                    $q->where(DB::raw('nik'), 'like', '%'.strtolower($keyword).'%');
                });
            })
            ->addColumn('university', static function ($data) {
                return $data->university->name;
            })
            ->addColumn('period', static function ($data) {
                return $data->requirement->period->name;
            })
            ->filterColumn('university', static function($query, $keyword) {
                return $query->whereHas('university', static function($q) use($keyword) {
                    $q->where(DB::raw('LOWER(universities.name)'), 'like', '%'.strtolower($keyword).'%');
                });
            })
            ->addColumn('registered_at', function ($data) {
                return Daster::tanggal($data->created_at, 1, true);
            })
            ->editColumn('status', function($data) {
                return '<span class="btn btn-bold btn-sm btn-font-sm  btn-label-'.config('constant.registrant_status.badge.'.$data->status_registrant).'">'.config('constant.registrant_status.'.$data->status_registrant).'</span>';
            })
            ->filterColumn('status', static function($query, $keyword) {
                return $query->where('registrants.status', 'like', $keyword);
            })
            ->addColumn('action', function ($data) {

                $validation = auth()->user()->can('Pendaftar: Validasi') ? ' <a href="'.route($this->route.'.validation', [$data->primary]).'" class="btn btn-label-brand btn-icon btn-sm action-edit"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Validasi Persyaratan" data-boundary="window"><i class="la la-check-circle-o"></i></a>' : '';
                $destroy = auth()->user()->can('Pendaftar: Tambah, Ubah, Hapus') ? ' <a href="' . route($this->route . '.destroy', [$data->primary]) . '" class="btn btn-label-danger btn-icon btn-sm action-delete"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Hapus" data-boundary="window"><i class="fa fa-trash"></i></a>' : '';

                return $validation;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function validation($id)
    {
        $registrant = Registrant::where('id', $id)->with('user', 'university', 'requirements.item')->first();

        $data = [
            'title' => 'Validasi Pendaftar',
            'route' => $this->route,
            'registrant' => $registrant,
            'requirements' => $registrant->requirements
        ];

        return view($this->route.'.validation', $data);
    }

    public function store(Request $request, $id)
    {
        $registrant = Registrant::where('id', $id)->with('user', 'university', 'requirements.item')->first();

        foreach ($registrant->requirements as $requirement)
        {
            if ($request->has('validate_'.$requirement->id)) {
                $requirement->update([
                    'validation' => @$request->input('validate_' . $requirement->id)['checklist'] === 'on',
                    'note' => $request->input('validate_' . $requirement->id)['note'],
                    'validated_at' => now(),
                    'admin_id' => Auth::user()->id
                ]);
            }
        }

        $registrant->status = $request->result === 'on' ? 'Approve' : 'Reject';
        $registrant->save();

        //set nim
        $user = User::where('id',$registrant->user->id)->first();

        if($request->result === 'on'){
            $user->nim = $request->input('nim');
        }else{
            $user->nim = null;
        }

        $user->save();

        return response()->json(['success' => true, 'message' => 'Berhasil disimpan']);
    }
}
