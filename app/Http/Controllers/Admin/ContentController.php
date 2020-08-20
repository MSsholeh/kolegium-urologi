<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Daster;
use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ContentController extends Controller
{
    private $title;
    private $route;

    public function __construct()
    {
        $this->title = 'Konten';
        $this->route = 'admin.content';
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
        $query = Content::select('*');

        if ($datatables->getRequest()->has('trashed')) {
            $query->onlyTrashed();
        }

        return $datatables->eloquent($query)
            ->addIndexColumn()
            ->addColumn('log', function ($data) {
                return $data->admin->name.' <br>'.Daster::tanggal($data->updated_at, 1, true);;
            })
            ->orderColumn('log', 'updated_at $1')
            ->addColumn('action', function ($data) {
                return ($data->trashed() ?
                        '<a href="'.route($this->route.'.restore', [$data->id]).'" class="btn btn-label-success btn-icon btn-sm action-other"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Restore" data-boundary="window"><i class="fa fa-trash-restore"></i></a>' :
                        '<a href="'.route($this->route.'.edit', [$data->id]).'" class="btn btn-label-brand btn-icon btn-sm action-edit"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Edit" data-boundary="window"><i class="fa fa-pencil-alt"></i></a>').

                    ' <a href="'.route($this->route.'.destroy', [$data->id]).'" class="btn btn-label-danger btn-icon btn-sm action-delete"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="'.($data->trashed() ? 'Hapus Permanen' : 'Hapus').'" data-boundary="window"><i class="fa fa-trash"></i></a>';
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
            'breadcrumbs' => ['Konten', 'Halaman Utama', $this->title => route($this->route.'.index'), 'Tambah'],
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
            'content' => 'required|string'
        ]);

        $store = new Content;
        $store->title = $request->title;
        $store->content = $request->input('content');
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
     * @param Content $content
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Content $content)
    {
        $data = [
            'title' => $this->title,
            'breadcrumbs' => ['Konten', 'Halaman Utama', $this->title => route($this->route.'.index'), 'Ubah'],
            'route' => $this->route,
            'value' => $content,
        ];

        return view($this->route.'.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Content $content
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Content $content)
    {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string'
        ]);

        $content->title = $request->title;
        $content->content = $request->input('content');
        $content->save();

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
        $parse = 'dihapus.';

        if ($content = Content::find($id)) {

            $content->delete();

        } else {

            $content = Content::withTrashed()->find($id);
            $content->forceDelete();

            $parse = 'dihapus permanen.';

        }

        return response()->json(['success' => true, 'message' => 'Data berhasil '.$parse]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($id)
    {
        $content = Content::withTrashed()->findOrFail($id);
        $content->restore();

        return response()->json(['success' => true, 'message' => 'Data berhasil dikembalikan.']);
    }
}
