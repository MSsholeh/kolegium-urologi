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
use WordTemplate;
use function foo\func;
use Carbon\Carbon;

class SertifikatController extends Controller
{
    private $title;
    private $route;

    public function __construct()
    {
        $this->title = 'Sertifikat Terbit';
        $this->route = 'admin.sertifikat';
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
        $query = User::select('*')->whereNotNull('no_sertifikat')->with('university');
        $admin = Auth::user();
        if ($admin->isAdminUniversity()) {
           $query->where('university_id', $admin->university_id);
        }

        return $datatables->eloquent($query)
            ->addIndexColumn()
            ->editColumn('date_sertifikat', static function ($data) {
                return Daster::tanggal($data->date_sertifikat, 1, false);
            })
            ->editColumn('universitas', static function ($data) {
                return $data->university->name;
            })
            ->addColumn('action', function ($data) {

                $download = '<a href="' .route($this->route.'.download', [$data->id]). '" class="btn btn-label-brand btn-icon btn-sm"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Download Sertifikat" data-boundary="window"><i class="fa fa-download"></i></a>' ;
                $edit = ' <a href="' . route($this->route . '.edit', [$data->id]) . '" class="btn btn-label-brand btn-icon btn-sm action-edit"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Edit Nomor Sertifikat" data-boundary="window"><i class="fa fa-edit"></i></a>' ;
                if (auth()->user()->can('Sertifikat: Lihat, Download')) {
                    return $download.(auth()->user()->can('Sertifikat: Ubah') ? $edit : '');
                }
            })
            ->rawColumns(['date_sertifikat', 'universitas', 'action'])
            ->make(true);
    }

    public function download($id){
        $file = public_path('sertifikat/template.rtf');
        $user = User::where('id',$id)->first();
        $diffYears = \Carbon\Carbon::now()->diffInYears(Carbon::parse($user->dob));

        $array = array(
            'nmrsertifikat' => $user->no_sertifikat,
            '[nama]' => $user->name,
            '[tempat_lahir]' => $user->pob,
            '[tanggal_lahir_in]' => Daster::tanggal($user->dob, 1, false),
            '[tanggal_lahir_en]' => Carbon::parse($user->dob)->format('F d, Y'),
            '[universitas]' => $user->university->name,
            'berlakusertifikat' => Daster::tanggal(Carbon::parse($user->dob)->addYear($diffYears+5), 1, false),
            'validsertifikat' => Carbon::parse($user->dob)->addYear($diffYears+5)->format('F d, Y'),
            'datesertifikat' => Daster::tanggal($user->date_sertifikat, 1, false),
        );

        $nama_file = 'Sertifikat-'.$user->name.'-'.$user->no_sertifikat.'.doc';
        ob_clean();
        header("Content-type: text/rtf; charset=UTF-8");
        header("Content-Disposition: attachment; filename=myfile.rtf");
        header("Expires: 0");
        return WordTemplate::export($file, $array, $nama_file);
    }

    public function edit($id)
    {
        $data = [
            'title' => $this->title,
            'breadcrumbs' => [$this->title => route($this->route.'.index')],
            'route' => $this->route,
            'user' => User::where('id',$id)->first(),
        ];

        return view($this->route.'.edit', $data);
    }

    public function update(Request $request)
    {
        $request->validate([
            'no_sertifikat' => 'required',
        ]);

        $user=User::whereid($request->id)->firstOrFail();
        $user->no_sertifikat = $request->no_sertifikat;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Data berhasil disimpan.']);
    }
}
