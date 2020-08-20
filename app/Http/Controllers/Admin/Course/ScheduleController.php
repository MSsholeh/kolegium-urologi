<?php

namespace App\Http\Controllers\Admin\Course;

use App\Helpers\Daster;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseRequirement;
use App\Models\CourseSchedule;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ScheduleController extends Controller
{
    private $title;
    private $route;

    function __construct()
    {
        $this->title = 'Jadwal';
        $this->route = 'admin.course.schedule';
    }

    /**
     * Display a listing of the resource.
     *
     * @param Course $course
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Course $course)
    {
        $data = [
            'title' => $this->title,
            'breadcrumbs' => ['Kursus' => route('admin.course.show', $course->id), $this->title => route($this->route.'.index', $course->id)],
            'route' => $this->route
        ];

        return view($this->route.'.index', $data);
    }

    /**
     * Show list data for Datatables.
     *
     * @param DataTables $datatables
     * @param Course $course
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function table(DataTables $datatables, Course $course): \Illuminate\Http\JsonResponse
    {
        $query = CourseSchedule::select('*')->with('participants')->where('course_id', $course->id);

        if ($datatables->getRequest()->has('trashed')) {
            $query->onlyTrashed();
        }

        if (auth()->user()->cannot('Kursus: Lihat Semua')) {
            $query->where('university_id', auth()->user()->university_id);
        }

        return $datatables->eloquent($query)
            ->addIndexColumn()
            ->addColumn('date', static function ($data) {
                return Daster::tanggal($data->started_at).' - '.Daster::tanggal($data->ended_at);
            })
            ->addColumn('participant', static function ($data) {
                return $data->countApprovedParticipant();
            })
            ->addColumn('log', static function ($data) {
                return ($data->admin->name ?? '').' <br>'.Daster::tanggal($data->updated_at, 1, true);
            })
            ->orderColumn('log', 'updated_at $1')
            ->addColumn('action', function ($data) {
                $duplicate = ' <a href="'.route($this->route.'.duplicate', [$data->course_id, $data->id]).'" class="btn btn-label-brand btn-icon btn-sm action-confirm" data-confirm="Duplikat" data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Duplikat Jadwal" data-boundary="window"><i class="la la-copy"></i></a>';
                $participants = $data->trashed() !== true ? ' <a href="'.route($this->route.'.participant.index', [$data->course_id, $data->id]).'" class="btn btn-label-brand btn-icon btn-sm shoot"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Peserta Kursus" data-boundary="window"><i class="la la-users"></i></a>' : '';
                $subject = $data->trashed() !== true ? ' <a href="'.route($this->route.'.subject.index', [$data->course_id, $data->id]).'" class="btn btn-label-brand btn-icon btn-sm shoot"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Materi Kursus" data-boundary="window"><i class="la la-book"></i></a>' : '';
                $edit = $data->trashed() !== true ? ' <a href="'.route($this->route.'.edit', [$data->course_id, $data->id]).'" class="btn btn-label-brand btn-icon btn-sm action-edit"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Edit" data-boundary="window"><i class="la la-edit"></i></a>' : '';
                $delete = ' <a href="'.route($this->route.'.destroy', [$data->course_id, $data->id]).'" class="btn btn-label-danger btn-icon btn-sm action-delete"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="'.($data->trashed() ? 'Hapus Permanen' : 'Hapus').'" data-boundary="window"><i class="la la-trash"></i></a>';

                return $participants.$subject.$edit.$delete.$duplicate;
            })
            ->rawColumns(['log', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Course $course
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Course $course)
    {
        $data = [
            'title' => $this->title,
            'route' => $this->route,
            'course' => $course
        ];

        return view($this->route.'.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Course $course
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Course $course, Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'date' => 'required',
            'convenor' => 'required|string',
            'location' => 'required|string',
            'quota' => 'required',
        ]);

        $date = explode(' - ', $request->date);

        if(auth()->user()->cannot('Kursus: Lihat Semua')) {
            $university = auth()->user()->university_id;
        } else {
            $university = $request->university_id;
        }

        $schedule = $course->schedule()->create([
            'started_at' => Daster::parseTanggal($date[0]),
            'ended_at' => Daster::parseTanggal($date[1]),
            'convenor' => $request->convenor,
            'location' => $request->location,
            'description' => $request->description,
            'quota' => $request->quota,
            'university_id' => $university
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

    public function edit(Course $course, CourseSchedule $schedule)
    {
        $data = [
            'title' => $this->title,
            'route' => $this->route,
            'course' => $course,
            'schedule' => $schedule,
            'types' => ['Text', 'Checkbox', 'File']
        ];

        return view($this->route.'.edit', $data);
    }

    public function update(Course $course, CourseSchedule $schedule, Request $request)
    {
        $request->validate([
            'date' => 'required',
            'convenor' => 'required|string',
            'location' => 'required|string',
            'description' => 'required'
        ]);

        $date = explode(' - ', $request->date);

        $schedule->update([
            'started_at' => Daster::parseTanggal($date[0]),
            'ended_at' => Daster::parseTanggal($date[1]),
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

    public function destroy($examId, $scheduleId)
    {
        $parse = 'dihapus.';

        if ($data = CourseSchedule::find($scheduleId)) {

            $data->delete();

        } else {

            $data = CourseSchedule::withTrashed()->find($scheduleId);
            $data->forceDelete();

            $parse = 'dihapus permanen.';

        }

        return response()->json(['success' => true, 'message' => 'Data berhasil '.$parse]);
    }

    public function restore($examId, $scheduleId)
    {
        $data = CourseSchedule::withTrashed()->findOrFail($scheduleId);
        $data->restore();

        return response()->json(['success' => true, 'message' => 'Data berhasil dikembalikan.']);
    }

    public function duplicate(Course $course, CourseSchedule $schedule)
    {
        $replicate = $schedule->replicate();
        $replicate->push();
        $replicate->requirements()->createMany($schedule->requirements->toArray());

        return response()->json(['success' => true, 'message' => 'Data berhasil diduplikasi.']);
    }


}
