<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Daster;
use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\RegistrantGraduation;
use App\Models\Registrant;
use App\Models\RequirementGraduation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use function foo\func;
use Carbon\Carbon;

class RegistrantGraduationController extends Controller
{
    private $title;
    private $route;

    public function __construct()
    {
        $this->title = 'Pendaftar Ujian Nasional';
        $this->route = 'admin.registrant-graduation';
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
        $query = RegistrantGraduation::select('*', 'registrants_graduation.status as status_registrant', 'registrants_graduation.id as primary')
            ->with('user', 'university','requirement_graduation.period');

        $admin = Auth::user();
        if ($admin->isAdminUniversity()) {
            $query->where('registrants_graduation.university_id', $admin->university_id);
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
            ->addColumn('period', static function ($data) {
                return $data->requirement_graduation->period->name;
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

                $validation = auth()->user()->can('Pendaftar Ujian: Validasi') ? ' <a href="'.route($this->route.'.validation', [$data->primary]).'" class="btn btn-label-brand btn-icon btn-sm action-edit"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Validasi Persyaratan" data-boundary="window"><i class="la la-check-circle-o"></i></a>' : '';
                $destroy = auth()->user()->can('Pendaftar Ujian: Tambah, Ubah, Hapus') ? ' <a href="' . route($this->route . '.destroy', [$data->primary]) . '" class="btn btn-label-danger btn-icon btn-sm action-delete"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Hapus" data-boundary="window"><i class="fa fa-trash"></i></a>' : '';

                return $validation;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function validation($id)
    {
        $registrant = RegistrantGraduation::where('id', $id)->with('user', 'requirements_graduation.item')->first();

        $data = [
            'title' => 'Validasi Pendaftar Ujian Nasional',
            'route' => $this->route,
            'registrant' => $registrant,
            'requirements' => $registrant->requirements_graduation
        ];

        return view($this->route.'.validation', $data);
    }

    public function create()
    {
        $data = [
            'title' => $this->title,
            'breadcrumbs' => [$this->title => route($this->route.'.index')],
            'route' => $this->route,
            'requirement' => RequirementGraduation::where(['status' => 'Active'])->first(),
        ];

        return view($this->route.'.create', $data);
    }

 public function store(Request $request)
    {
        $rules = [];
        $message = [];
        $requirement = RequirementGraduation::where(['status' => 'Active'])->first();
        $universityRequirements = $requirement->items;
        foreach ($universityRequirements as $item)
        {
            $rule = [];

            if ($item->required && $item->type !== 'Checkbox') {
                $rule[] = 'required';
                $message['requirement_' . $item->id.'.required'] = 'Kolom "'.$item->name.'" harus diisi.';
            } else {
                $rule[] = 'nullable';
            }

            if ($item->type === 'File') {
                $rule[] = 'file';
            }

            $rules['requirement_' . $item->id] = implode('|', $rule);
        }

        $request->validate(
            $rules, $message
        );

        $user = Registrant::where('id',$request->registrant_id)->first();

        $registrant = RegistrantGraduation::create([
            'user_id' => $user->user_id,
            'university_id' => $user->university_id,
            'requirement_graduation_id' => $requirement->id,
            'status' => 'Request'
        ]);

        $items = [];
        foreach ($universityRequirements as $item)
        {
            $field = 'requirement_'.$item->id;

            if (($item->type === 'File') && $request->hasFile($field)) {

                $value = $request->file('requirement_'.$item->id)->store('requirement/'.$user->university_id.'/'.$user->user_id, 'local');

            } else if ($item->type === 'Checkbox' && $request->has('requirement_'.$item->id)) {

                $value = $request->input('requirement_'.$item->id) ? true : false;

            } else {

                $value = $request->has('requirement_'.$item->id) ? $request->input('requirement_'.$item->id) : null;

            }

            if ($value) {
                $items[] = [
                    'requirement_graduation_item_id' => $item->id,
                    'value' => $value
                ];
            }
        }

        $registrant->requirements_graduation()->createMany(
            $items
        );

        return response()->json(['success' => true, 'message' => 'Pendaftaran Ujian Nasional berhasil.']);
    }
}
