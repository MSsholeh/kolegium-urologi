<?php

namespace App\Http\Controllers\Admin\Course;

use App\Helpers\Daster;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CourseController extends Controller
{
    private $title;
    private $route;

    function __construct()
    {
        $this->title = 'Kursus';
        $this->route = 'admin.course';
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
        $query = Course::select('*');

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
                $schedule = ' <a href="'.route($this->route.'.show', [$data->id]).'" class="btn btn-label-info btn-icon btn-sm shoot"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Jadwal Kursus" data-boundary="window"><i class="la la-bars"></i></a>';
                $restore = ' <a href="'.route($this->route.'.restore', [$data->id]).'" class="btn btn-label-success btn-icon btn-sm action-other"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Restore" data-boundary="window"><i class="la la-trash"></i></a>';
                $edit = ' <a href="'.route($this->route.'.edit', [$data->id]).'" class="btn btn-label-brand btn-icon btn-sm action-edit"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Edit" data-boundary="window"><i class="la la-edit"></i></a>';
                $delete = ' <a href="'.route($this->route.'.destroy', [$data->id]).'" class="btn btn-label-danger btn-icon btn-sm action-delete"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="'.($data->trashed() ? 'Hapus Permanen' : 'Hapus').'" data-boundary="window"><i class="la la-trash"></i></a>';

                return $data->trashed() ? $restore.$delete : $schedule.$edit.$delete;
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
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string'
        ]);

        $store = new Course;
        $store->name = $request->name;
        $store->description = $request->description;
        $store->save();

        return response()->json(['success' => true, 'message' => 'Data berhasil disimpan.']);
    }

    /**
     * Display the specified resource.
     *
     * @param Course $course
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Course $course)
    {
        $data = [
            'title' => 'Jadwal Kursus',
            'breadcrumbs' => [$this->title => route($this->route.'.index'), $course->name],
            'route' => $this->route,
            'course' => $course
        ];

        return view($this->route.'.show', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Course $course
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Course $course)
    {
        $data = [
            'title' => $this->title,
            'route' => $this->route,
            'value' => $course
        ];

        return view($this->route.'.edit', $data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string'
        ]);

        $course->name = $request->name;
        $course->description = $request->description;
        $course->save();

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

        if ($content = Course::find($id)) {

            $content->delete();

        } else {

            $content = Course::withTrashed()->find($id);
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
        $content = Course::withTrashed()->findOrFail($id);
        $content->restore();

        return response()->json(['success' => true, 'message' => 'Data berhasil dikembalikan.']);
    }

}
