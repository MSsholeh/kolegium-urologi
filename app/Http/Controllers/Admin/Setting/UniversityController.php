<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Helpers\Daster;
use App\Http\Controllers\Controller;
use App\Models\University;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UniversityController extends Controller
{
    private $title;
    private $route;

    public function __construct()
    {
        $this->title = 'Universitas';
        $this->route = 'admin.setting.university';
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
        $query = University::select('*');

        return $datatables->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', function($data) {
                return Daster::tanggal($data->created_at, 1, true);
            })
            ->addColumn('action', function ($data) {
                if(auth()->user()->can('Universitas: Tambah, Ubah, Hapus')) {
                    return ' <a href="' . route($this->route . '.edit', [$data->id]) . '" class="btn btn-label-brand btn-icon btn-sm action-edit"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Edit" data-boundary="window"><i class="fa fa-pencil-alt"></i></a>' .
                        ' <a href="' . route($this->route . '.destroy', [$data->id]) . '" class="btn btn-label-danger btn-icon btn-sm action-delete"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Hapus" data-boundary="window"><i class="fa fa-trash"></i></a>';
                }
            })
            ->rawColumns(['action'])
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
            'title' => 'Tambah '.$this->title,
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
            'location' => 'required|string',
        ]);

        $store = new University;
        $store->name = $request->name;
        $store->location = $request->location;
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
     * @param University $university
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(University $university)
    {
        $data = [
            'title' => 'Ubah '.$this->title,
            'route' => $this->route,
            'value' => $university,
        ];

        return view($this->route.'.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param University $university
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, University $university)
    {
        $request->validate([
            'name' => 'required|string',
            'location' => 'required|string',
        ]);

        $university->name = $request->name;
        $university->location = $request->location;
        $university->save();

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
        University::find($id)->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
