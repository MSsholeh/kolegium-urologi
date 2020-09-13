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

class DbSpuController extends Controller
{
    private $title;
    private $route;

    public function __construct()
    {
        $this->title = 'Database SPU';
        $this->route = 'admin.database-spu';
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
        $query = ExamParticipant::with('registrant_graduation.user','registrant_graduation.university')->where('graduate','Lulus')->get();

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('name', function ($query) {
                return $query->registrant_graduation->user->name ?? '';
            })
            ->addColumn('npa', function ($query) {
                return $query->registrant_graduation->user->npa ?? '';
            })
            ->addColumn('university', function ($query) {
                return $query->registrant_graduation->university->name ?? '';
            })
            ->rawColumns(['name','npa','university'])
            ->make(true);
    }

    public function create()
    {
        $data = [
            'title' => 'Input Database SPU',
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

        return redirect()->back()->withSuccess('Input Database SPU Berhasil!');
    }
}
