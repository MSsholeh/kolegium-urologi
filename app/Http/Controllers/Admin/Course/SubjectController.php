<?php

namespace App\Http\Controllers\Admin\Course;

use App\Helpers\Daster;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\ExamSchedule;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class SubjectController extends Controller
{
    private $title;
    private $route;

    function __construct()
    {
        $this->title = 'Materi';
        $this->route = 'admin.course.schedule.subject';
    }

    /**
     * Display a listing of the resource.
     *
     * @param Course $course
     * @param CourseSchedule $schedule
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Course $course, CourseSchedule $schedule)
    {
        $data = [
            'title' => $this->title,
            'course' => $course,
            'schedule' => $schedule,
            'breadcrumbs' => [
                'Kursus' => route('admin.course.index'),
                $course->name => route('admin.course.show', [$course->id]),
                $this->title => route($this->route.'.index', [$course->id, $schedule->id]),
            ],
            'route' => $this->route
        ];

        return view($this->route.'.index', $data);
    }

    /**
     * Show list data for Datatables.
     *
     * @param DataTables $datatables
     * @param Course $course
     * @param CourseSchedule $schedule
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function table(DataTables $datatables, Course $course, CourseSchedule $schedule): \Illuminate\Http\JsonResponse
    {
        $query = Subject::select('*')->where('course_schedule_id', $schedule->id);

        return $datatables->eloquent($query)
            ->addIndexColumn()
            ->editColumn('attachment', static function($data) {
                return '<a href="'.route('web.file', ['path' => $data->attachment]).'" class="btn btn-sm btn-icon btn-brand" target="_blank"><i class="la la-file"></i></a>';
            })
            ->addColumn('action', function ($data) use($course) {
                $edit = ' <a href="'.route($this->route.'.edit', [$course->id, $data->course_schedule_id, $data->id]).'" class="btn btn-label-brand btn-icon btn-sm action-edit"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Edit" data-boundary="window"><i class="la la-edit"></i></a>';
                $delete = ' <a href="'.route($this->route.'.destroy', [$course->id, $data->course_schedule_id, $data->id]).'" class="btn btn-label-danger btn-icon btn-sm action-delete"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Hapus" data-boundary="window"><i class="la la-trash"></i></a>';

                return $edit.$delete;
            })
            ->rawColumns(['attachment', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Course $course
     * @param CourseSchedule $schedule
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Course $course, CourseSchedule $schedule)
    {
        $data = [
            'title' => $this->title,
            'route' => $this->route,
            'course' => $course,
            'schedule' => $schedule
        ];

        return view($this->route.'.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Course $course
     * @param CourseSchedule $schedule
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Course $course, CourseSchedule $schedule): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required',
            'attachment' => 'required|file'
        ]);

        $schedule->subjects()->create([
            'title' => $request->title,
            'description' => $request->description,
            'attachment' => $request->file('attachment')->store('course/' .$course->id.'/schedule/'.$schedule->id.'/subject', 'local'),
        ]);

        return response()->json(['success' => true, 'message' => 'Data berhasil disimpan.']);
    }

    public function edit(Course $course, CourseSchedule $schedule, Subject $subject)
    {
        $data = [
            'title' => $this->title,
            'route' => $this->route,
            'course' => $course,
            'schedule' => $schedule,
            'subject' => $subject
        ];

        return view($this->route.'.edit', $data);
    }

    public function update(Request $request, Course $course, CourseSchedule $schedule, Subject $subject)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required',
            'attachment' => 'nullable|file'
        ]);

        $subject->title = $request->title;
        $subject->description = $request->description;

        if ($request->hasFile('attachment')) {
            Storage::disk('local')->delete($subject->attachment);
            $subject->attachment = $request->file('attachment')->store('course/' .$course->id.'/schedule/'.$schedule->id.'/subject', 'local');
        }

        $subject->save();


        return response()->json(['success' => true, 'message' => 'Data berhasil disimpan.']);
    }

    public function destroy($examId, $scheduleId)
    {
        $parse = 'dihapus.';

        if ($data = Subject::find($scheduleId)) {

            $data->delete();

        } else {

            $data = Subject::withTrashed()->find($scheduleId);
            $data->forceDelete();

            $parse = 'dihapus permanen.';

        }

        return response()->json(['success' => true, 'message' => 'Data berhasil '.$parse]);
    }
}
