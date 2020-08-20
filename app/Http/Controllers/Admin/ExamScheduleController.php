<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Daster;
use App\Http\Controllers\Controller;
use App\Models\ExamSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ExamScheduleController extends Controller
{
    private $title;
    private $route;

    public function __construct()
    {
        $this->title = 'Jadwal Ujian';
        $this->route = 'admin.exam';
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

        return view('admin.exam.index', $data);
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
        $query = ExamSchedule::select('*')->with(['university', 'period']);

        $admin = Auth::user();
        if ($admin->isAdminUniversity()) {
            $query->where('university_id', $admin->university_id);
        }

        return $datatables->eloquent($query)
            ->addIndexColumn()
            ->editColumn('started_at', function($data) {
                return Daster::tanggal($data->started_at);
            })
            ->editColumn('ended_at', function($data) {
                return Daster::tanggal($data->ended_at);
            })
            ->addColumn('period', function($data) {
                return $data->period->name;
            })
            ->addColumn('university', function($data) {
                return $data->university->name;
            })
            ->addColumn('action', function ($data) {
                $action = '';
                if (auth()->user()->can('Peserta Ujian: Lihat')) {
                    $action .= ' <a href="' . route($this->route . '.participants.index', [$data->id]) . '" class="btn btn-label-brand btn-icon btn-sm shoot"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Peserta Ujian" data-boundary="window"><i class="fa fa-users"></i></a>';
                }
                if (auth()->user()->can('Jadwal Ujian: Ubah, Hapus')) {
                    $action .= ' <a href="' . route($this->route . '.edit', [$data->id]) . '" class="btn btn-label-brand btn-icon btn-sm action-edit"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Edit" data-boundary="window"><i class="fa fa-pencil-alt"></i></a>' .
                        ' <a href="' . route($this->route . '.destroy', [$data->id]) . '" class="btn btn-label-danger btn-icon btn-sm action-delete"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Hapus" data-boundary="window"><i class="fa fa-trash"></i></a>';
                }
                return $action;
            })
            ->rawColumns(['log', 'action'])
            ->make(true);
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
            'title' => 'required|string',
            'date' => 'required|string',
            'period_id' => 'required',
            'university_id' => 'required',
        ]);

        $date = explode(' - ', $request->date);

        $store = new ExamSchedule;
        $store->title = $request->title;
        $store->started_at = Daster::parseTanggal($date[0]);
        $store->ended_at = Daster::parseTanggal($date[1]);
        $store->period_id = $request->period_id;
        $store->university_id = $request->university_id;
        $store->description = $request->description;
        $store->save();

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
     * @param ExamSchedule $examSchedule
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(ExamSchedule $exam)
    {
        $data = [
            'title' => $this->title,
            'route' => $this->route,
            'value' => $exam,
        ];

        return view($this->route.'.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param ExamSchedule $examSchedule
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, ExamSchedule $exam)
    {
        $request->validate([
            'title' => 'required|string',
            'date' => 'required|string',
            'period_id' => 'required',
            'university_id' => 'required',
        ]);

        $date = explode(' - ', $request->date);

        $exam->title = $request->title;
        $exam->started_at = Daster::parseTanggal($date[0]);
        $exam->ended_at = Daster::parseTanggal($date[1]);
        $exam->period_id = $request->period_id;
        $exam->university_id = $request->university_id;
        $exam->description = $request->description;
        $exam->save();

        return response()->json(['success' => true, 'message' => 'Data berhasil disimpan.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        ExamSchedule::find($id)->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
