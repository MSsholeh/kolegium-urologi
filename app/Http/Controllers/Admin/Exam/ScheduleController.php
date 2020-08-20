<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Helpers\Daster;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamRequirement;
use App\Models\ExamSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Yajra\DataTables\DataTables;

class ScheduleController extends Controller
{
    private $title;
    private $route;

    function __construct()
    {
        $this->title = 'Jadwal';
        $this->route = 'admin.exam.schedule';
    }

    /**
     * Display a listing of the resource.
     *
     * @param Exam $exam
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Exam $exam)
    {
        $data = [
            'title' => $this->title,
            'breadcrumbs' => ['Ujian' => route('admin.exam.show', $exam->id), $this->title => route($this->route.'.index')],
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
    public function table(DataTables $datatables, Exam $exam)
    {
        $query = ExamSchedule::select('*')->with('participants')->where('exam_id', $exam->id);

        if (auth()->user()->cannot('Ujian: Lihat Semua')) {
            $query->where('university_id', auth()->user()->university_id);
        }

        return $datatables->eloquent($query)
            ->addIndexColumn()
            ->editColumn('event_date', static function ($data) {
                return Daster::tanggal($data->event_date, 2);
            })
            ->addColumn('participant', static function ($data) {
                return $data->countRegistrant();
            })
            ->addColumn('log', static function ($data) {
                return $data->admin->name.' <br>'.Daster::tanggal($data->updated_at, 1, true);
            })
            ->orderColumn('log', 'updated_at $1')
            ->addColumn('action', function ($data) {
                $duplicate = ' <a href="'.route($this->route.'.duplicate', [$data->exam_id, $data->id]).'" class="btn btn-label-brand btn-icon btn-sm action-confirm" data-confirm="Duplikat" data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Duplikat Jadwal" data-boundary="window"><i class="la la-copy"></i></a>';
                $participants = ' <a href="'.route($this->route.'.participant.index', [$data->exam_id, $data->id]).'" class="btn btn-label-brand btn-icon btn-sm shoot"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Peserta Ujian" data-boundary="window"><i class="la la-users"></i></a>';
                $edit = ' <a href="'.route($this->route.'.edit', [$data->exam_id, $data->id]).'" class="btn btn-label-brand btn-icon btn-sm action-edit"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Edit" data-boundary="window"><i class="la la-edit"></i></a>';
                $delete = ' <a href="'.route($this->route.'.destroy', [$data->exam_id, $data->id]).'" class="btn btn-label-danger btn-icon btn-sm action-delete"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="'.($data->trashed() ? 'Hapus Permanen' : 'Hapus').'" data-boundary="window"><i class="la la-trash"></i></a>';

                return $participants.$edit.$delete.$duplicate;
            })
            ->rawColumns(['log', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Exam $exam
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Exam $exam)
    {
        $data = [
            'title' => $this->title,
            'route' => $this->route,
            'exam' => $exam
        ];

        return view($this->route.'.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Exam $exam
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Exam $exam, Request $request)
    {
        $request->merge([
            'event_date' => Daster::parseTanggal($request->event_date)
        ]);

        $request->validate([
            'period_id' => 'required|string',
            'event_date' => 'required|string',
            'convenor' => 'required|string',
            'location' => 'required|string',
            'quota' => 'required'
        ]);

        $schedule = $exam->schedule()->create([
            'period_id' => $request->period_id,
            'event_date' => $request->event_date,
            'convenor' => $request->convenor,
            'location' => $request->location,
            'description' => $request->description,
            'quota' => $request->quota,
            'university_id' => auth()->user()->university_id
        ]);

        $requirements = [];
        foreach ($request->requirements as $require) {
            $requirements[] = [
                'name' => $require['name'],
                'type' => $require['type'],
                'required' => @$require['required'] ? true : false
            ];
        }

        $schedule->requirements()->createMany($requirements);

        return response()->json(['success' => true, 'message' => 'Data berhasil disimpan.']);
    }

    public function edit(Exam $exam, ExamSchedule $schedule)
    {
        $data = [
            'title' => $this->title,
            'route' => $this->route,
            'exam' => $exam,
            'schedule' => $schedule,
            'types' => ['Text', 'Checkbox', 'File']
        ];

        return view($this->route.'.edit', $data);
    }

    public function update(Exam $exam, ExamSchedule $schedule, Request $request)
    {
        $request->merge([
            'event_date' => Daster::parseTanggal($request->event_date)
        ]);

        $request->validate([
            'period_id' => 'required|string',
            'event_date' => 'required|date',
            'convenor' => 'required|string',
            'location' => 'required|string',
            'description' => 'required'
        ]);

        $schedule->update([
            'period_id' => $request->period_id,
            'event_date' => $request->event_date,
            'convenor' => $request->convenor,
            'location' => $request->location,
            'description' => $request->description,
        ]);

        $exist = $schedule->requirements;

        $requirements = [];
        $updated = [];
        foreach ($request->requirements as $require) {
            $store = [
                'name' => $require['name'],
                'type' => $require['type'],
                'required' => @$require['required'] ? true : false
            ];

            if (@$require['id']) {
                $schedule->requirements()
                    ->where('id', $require['id'])
                    ->update($store);

                $updated[] = $require['id'];
            } else {
                $requirements[] = $store;
            }
        }

        if (count($requirements) > 0) {
            $schedule->requirements()->createMany($requirements);
        }

        $diff = $exist->pluck('id')->diff($updated);

        $schedule->requirements()->whereIn('id', $diff)->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil disimpan.']);
    }

    public function destroy($exam, $id)
    {
        $parse = 'dihapus.';

        if ($data = ExamSchedule::find($id)) {

            $data->delete();

        } else {

            $data = ExamSchedule::withTrashed()->find($id);
            $data->forceDelete();

            $parse = 'dihapus permanen.';

        }

        return response()->json(['success' => true, 'message' => 'Data berhasil '.$parse]);
    }

    public function restore($exam, $id)
    {
        $data = ExamSchedule::withTrashed()->findOrFail($id);
        $data->restore();

        return response()->json(['success' => true, 'message' => 'Data berhasil dikembalikan.']);
    }

    public function duplicate(Exam $exam, ExamSchedule $schedule)
    {
        $replicate = $schedule->replicate();
        $replicate->push();
        $replicate->requirements()->createMany($schedule->requirements->toArray());

        return response()->json(['success' => true, 'message' => 'Data berhasil diduplikasi.']);
    }

}
