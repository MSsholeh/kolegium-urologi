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

class RegistrantValidationController extends Controller
{
    private $title;
    private $route;

    public function __construct()
    {
        $this->title = 'Validasi Ujian Pendaftaran';
        $this->route = 'admin.registrant-validation';
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

    public function table(DataTables $datatables)
    {
        $query = Registrant::select('*', 'registrants.status as status_registrant', 'registrants.id as primary')
            ->where('status','Approve')
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
            ->filterColumn('university', static function($query, $keyword) {
                return $query->whereHas('university', static function($q) use($keyword) {
                    $q->where(DB::raw('LOWER(universities.name)'), 'like', '%'.strtolower($keyword).'%');
                });
            })
            ->addColumn('status', static function ($data) {
                if($data->graduate=="Lulus"){
                    $status = '<span class="badge badge-success">Lulus</span>';
                }else if($data->graduate=="Tidak Lulus"){
                    $status = '<span class="badge badge-danger">Tidak lulus</span>';
                }else{
                    $status = '<span class="badge badge-warning">Menunggu Konfirmasi</span>';
                }
                return $status;
            })
            ->addColumn('action', function ($data) {

                $validation = auth()->user()->can('Pendaftar: Validasi') ? ' <a href="'.route($this->route.'.validation', [$data->primary]).'" class="btn btn-label-brand btn-icon btn-sm action-edit"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Validasi Persyaratan" data-boundary="window"><i class="la la-check-circle-o"></i></a>' : '';
                $destroy = auth()->user()->can('Pendaftar: Tambah, Ubah, Hapus') ? ' <a href="' . route($this->route . '.destroy', [$data->primary]) . '" class="btn btn-label-danger btn-icon btn-sm action-delete"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Hapus" data-boundary="window"><i class="fa fa-trash"></i></a>' : '';

                return $validation;
            })
            ->rawColumns(['status','action'])
            ->make(true);
    }

    public function validation($id)
    {
        $registrant = Registrant::where('id', $id)->with('user', 'university', 'requirements.item')->first();

        $data = [
            'title' => 'Validasi Ujian Pendaftar',
            'route' => $this->route,
            'registrant' => $registrant,
            'requirements' => $registrant->requirements
        ];

        return view($this->route.'.validation', $data);
    }

    public function store(Request $request, $id)
    {
        $registrant = Registrant::where('id', $id)->with('user', 'university', 'requirements.item')->first();

        //set graduate pendaftaran
        $registrant->graduate = $request->input('graduate');
        $registrant->save();

        //set nim
        $user = User::where('id',$registrant->user->id)->first();
        if($registrant->graduate=="Lulus"){
            $user->nim = $request->input('nim');
            $user->tahap_kompetensi = "Tahap Pembekalan";
        }else{
            $user->nim = null;
        }
        $user->save();

        return response()->json(['success' => true, 'message' => 'Berhasil disimpan']);
    }
}
