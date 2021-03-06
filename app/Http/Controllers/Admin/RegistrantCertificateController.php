<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Daster;
use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\RegistrantCertificate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use function foo\func;
use Carbon\Carbon;

class RegistrantCertificateController extends Controller
{
    private $title;
    private $route;

    public function __construct()
    {
        $this->title = 'Pendaftar Sertifikat';
        $this->route = 'admin.registrant-certificate';
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
        $query = RegistrantCertificate::select('*', 'registrants_certificate.status as status_registrant', 'registrants_certificate.id as primary')
            ->with('user');

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

                $validation = auth()->user()->can('Pendaftar Sertifikat: Validasi') ? ' <a href="'.route($this->route.'.validation', [$data->primary]).'" class="btn btn-label-brand btn-icon btn-sm action-edit"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Validasi Persyaratan" data-boundary="window"><i class="la la-check-circle-o"></i></a>' : '';
                $destroy = auth()->user()->can('Pendaftar Sertifikat: Tambah, Ubah, Hapus') ? ' <a href="' . route($this->route . '.destroy', [$data->primary]) . '" class="btn btn-label-danger btn-icon btn-sm action-delete"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Hapus" data-boundary="window"><i class="fa fa-trash"></i></a>' : '';

                return $validation;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function validation($id)
    {
        $registrant = RegistrantCertificate::where('id', $id)->with('user', 'requirements_certificate.item')->first();

        $data = [
            'title' => 'Validasi Pendaftar Sertifikat',
            'route' => $this->route,
            'registrant' => $registrant,
            'requirements' => $registrant->requirements_certificate
        ];

        return view($this->route.'.validation', $data);
    }

    public function store(Request $request, $id)
    {
        $registrant = RegistrantCertificate::where('id', $id)->with('user', 'requirements_certificate.item')->first();

        foreach ($registrant->requirements_certificate as $requirement)
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

        //set no sertifikat
        $user = User::where('id',$registrant->user->id)->first();

        if($request->result === 'on'){
            $user->no_sertifikat = $request->input('no_sertifikat');
            $user->date_sertifikat = Carbon::now();
        }else{
            $user->no_sertifikat = null;
            $user->date_sertifikat = null;
        }

        $user->save();

        return response()->json(['success' => true, 'message' => 'Berhasil disimpan']);
    }
}
