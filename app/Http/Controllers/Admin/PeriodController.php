<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Daster;
use App\Http\Controllers\Controller;
use App\Models\Period;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use function foo\func;

class PeriodController extends Controller
{
    private $title;
    private $route;

    public function __construct()
    {
        $this->title = 'Periode';
        $this->route = 'admin.period';
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
        $query = Period::select('*');

        return $datatables->eloquent($query)
            ->addIndexColumn()
            ->editColumn('started_at', function($data) {
                return Daster::tanggal($data->started_at);
            })
            ->editColumn('ended_at', function($data) {
                return Daster::tanggal($data->ended_at);
            })
            ->editColumn('type', function($data) {
                return $data->type === 'registration' ? 'Periode Registrasi' : 'Periode Ujian';
            })
            ->addColumn('action', function ($data) {
                if (auth()->user()->can('Periode: Tambah, Ubah, Hapus')) {
                    return ' <a href="' . route($this->route . '.edit', [$data->id]) . '" class="btn btn-label-brand btn-icon btn-sm action-edit"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Edit" data-boundary="window"><i class="fa fa-pencil-alt"></i></a>' .
                        ' <a href="' . route($this->route . '.destroy', [$data->id]) . '" class="btn btn-label-danger btn-icon btn-sm action-delete"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Hapus" data-boundary="window"><i class="fa fa-trash"></i></a>';
                }
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
            'breadcrumbs' => ['Periode', 'Halaman Utama', $this->title => route($this->route.'.index'), 'Tambah'],
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
            'name' => 'required|string',
            'date' => 'required|string',
            'type' => 'required',
        ]);

        $date = explode(' - ', $request->date);

        $store = new Period;
        $store->name = $request->name;
        $store->started_at = Daster::parseTanggal($date[0]);
        $store->ended_at = Daster::parseTanggal($date[1]);
        $store->type = $request->type;
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
     * @param Period $period
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Period $period)
    {
        $data = [
            'title' => $this->title,
            'breadcrumbs' => ['Periode', 'Halaman Utama', $this->title => route($this->route.'.index'), 'Ubah'],
            'route' => $this->route,
            'value' => $period,
        ];

        return view($this->route.'.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Period $period
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Period $period)
    {
        $request->validate([
            'name' => 'required|string',
            'date' => 'required|string',
        ]);

        $date = explode(' - ', $request->date);

        $period->name = $request->name;
        $period->started_at = Daster::parseTanggal($date[0]);
        $period->ended_at = Daster::parseTanggal($date[1]);
        $period->save();

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
        Period::find($id)->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }

    public function change(Period $period)
    {
        if ( ! $period->active) {
            Period::where('active', true)->update(['active' => false]);
        }

        $period->active = ! $period->active;
        $period->save();

        return response()->json(['success' => true, 'message' => 'Data berhasil diubah']);
    }
}
