<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Daster;
use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\Registrant;
use App\Models\RegistrantGraduation;
use App\Models\ExamParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Ui\Presets\React;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Crypt;
use Session;

class ResidentController extends Controller
{
    private $title;
    private $route;

    public function __construct()
    {
        $this->title = 'Database Resident';
        $this->route = 'admin.resident';
    }

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
            ->addColumn('npa', function ($data) {
                return $data->user->npa;
            })
            ->filterColumn('npa', function($query, $keyword) {
                return $query->whereHas('user', function($q) use($keyword) {
                    $q->where(DB::raw('npa'), 'like', '%'.strtolower($keyword).'%');
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
            ->addColumn('period', static function ($data) {
                if(empty($data->requirement_id)){
                    return $data->user->tahun_masuk;
                }else{
                    return $data->requirement->period->name;
                }
            })

            ->rawColumns([])
            ->make(true);
    }

    public function create()
    {
        $data = [
            'title' => 'Input Database Resident',
            'breadcrumbs' => [$this->title => route($this->route.'.index')],
            'route' => $this->route
        ];

        return view($this->route.'.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => ['required', 'string', 'min:16', 'max:16', 'unique:users'],
            'npa' => ['required', 'string', 'min:5', 'unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed'],
            'university' => ['required', 'string', 'max:100'],
            'pob' => 'required',
            'dob' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'nim' => 'required',
        ]);

        User::create([
            'nik' => $request->nik,
            'npa' => $request->npa,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Crypt::encryptString($request->password),
            'university' => $request->university,
            'pob' => $request->pob,
            'dob' => $request->dob,
            'address' => $request->address,
            'phone' => $request->phone,
            'nim' => $request->nim,
            'tahun_masuk' => $request->tahun_masuk,

            'no_sertifikat' => $request->no_sertifikat,
            'date_sertifikat' => $request->date_sertifikat,
        ]);

        $user = User::where('npa',$request->npa)->first();

        Registrant::create([
            'user_id' => $user->id,
            'university_id' => $request->university_id,
            'status' => 'Approve',
            'graduate' => 'Lulus',
            'submission' => 1,
        ]);

        if($request->status_lulus == 'on'){
            $status_lulus = 'Lulus';

            RegistrantGraduation::create([
                'user_id' => $user->id,
                'university_id' => $request->university_id,
                'status' => 'Approve',
            ]);

            $registrant_graduation = RegistrantGraduation::where('user_id',$user->id)->first();

            ExamParticipant::create([
                'registrant_graduation_id' => $registrant_graduation->id,
                'graduate' => 'Lulus',
            ]);
        }else{
            $status_lulus = null;
        }

        //return response()->json(['success' => true, 'message' => 'Input Database Resident Berhasil.']);
        return redirect()->back()->withSuccess('Input Database Resident Berhasil!');
    }
}
