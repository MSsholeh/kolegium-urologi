<?php

namespace App\Http\Controllers\Admin\Course;

use App\Helpers\Daster;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseParticipant;
use App\Models\CourseSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use function foo\func;

class ParticipantController extends Controller
{
    private $title;
    private $route;

    public function __construct()
    {
        $this->title = 'Peserta Kursus';
        $this->route = 'admin.course.schedule.participant';
    }

    public function index(Course $course, CourseSchedule $schedule)
    {
        $data = [
            'title' => 'Peserta',
            'breadcrumbs' => [
                'Kursus',
                $schedule->course->name => route('admin.course.show', $course->id),
                'Peserta Kursus '.Daster::tanggal($schedule->started_at).' - '.Daster::tanggal($schedule->ended_at),

            ],
            'route' => $this->route,
            'course' => $course,
            'schedule' => $schedule,
        ];

        return view($this->route.'.index', $data);
    }

    public function table(DataTables $datatables, Course $course, CourseSchedule $schedule): \Illuminate\Http\JsonResponse
    {
        $query = CourseParticipant::select('*')->with('user')->where('course_schedule_id', $schedule->id);

        return $datatables->eloquent($query)
            ->addIndexColumn()
            ->editColumn('created_at', static function ($data) {
                return Daster::tanggal($data->created_at, 2, true);
            })
            ->editColumn('status', static function($data) {
                return '<span class="kt-badge kt-badge--inline kt-badge--'.config('constant.validation_status.badge.'.$data->status).'">'.$data->status.'</span>';
            })
            ->addColumn('name', static function ($data) {
                return $data->user->name ?? '';
            })
            ->addColumn('action', function ($data) use($course) {
//                $graduate = $data->status === 'Diterima' ? ' <a href="'.route($this->route.'.graduate', [$course->id, $data->course_schedule_id, $data->id]).'" class="btn btn-label-success btn-icon btn-sm action-edit"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Kelulusan" data-boundary="window"><i class="la la-graduation-cap"></i></a>' : '';
                $validation = ' <a href="'.route($this->route.'.validation', [$course->id, $data->course_schedule_id, $data->id]).'" class="btn btn-label-brand btn-icon btn-sm action-edit"  data-container="body" data-toggle="kt-tooltip" data-placement="top" title="Validasi Persyaratan" data-boundary="window"><i class="la la-check-circle-o"></i></a>';
                return $validation;
            })
            ->rawColumns(['graduate', 'status', 'action'])
            ->make(true);
    }

    public function validation(Course $course, CourseSchedule $schedule, CourseParticipant $participant)
    {
        $data = [
            'title' => 'Validasi Persyaratan',
            'route' => $this->route,
            'course' => $course,
            'schedule' => $schedule,
            'participant' => $participant,
            'requirements' => $participant->requirements()->with('requirement')->get(),
            'types' => ['Text', 'Checkbox', 'File']
        ];

        return view($this->route.'.validation', $data);
    }

    public function store(Request $request, Course $course, CourseSchedule $schedule, CourseParticipant $participant)
    {
        foreach ($participant->requirements as $requirement)
        {
            if ($request->has('validate_'.$requirement->id)) {
                $requirement->update([
                    'validation' => @$request->input('validate_' . $requirement->id)['checklist'] === 'on',
                    'note' => $request->input('validate_' . $requirement->id)['note'],
                    'validated_at' => now()
                ]);
            }
        }

        $participant->status = $request->result === 'on' ? 'Diterima' : 'Ditolak';
        $participant->save();

        return response()->json(['success' => true, 'message' => 'Berhasil disimpan']);
    }

    public function graduate(Course $course, CourseSchedule $schedule, CourseParticipant $participant)
    {
        $data = [
            'title' => 'Kelulusan',
            'route' => $this->route,
            'course' => $course,
            'schedule' => $schedule,
            'participant' => $participant
        ];

        return view($this->route.'.graduate', $data);
    }

    public function storeGraduate(Request $request, Course $course, CourseSchedule $schedule, CourseParticipant $participant)
    {
        $hasFile = $request->has('graduate') && $request->hasFile('certified');
        if ($hasFile) {
           Storage::disk('local')->delete($participant->certified);
        }

        $participant->graduate = $request->has('graduate');
        $participant->certified = $hasFile ? $request->file('certified')->store('participant/certified', 'local') : null;
        $participant->save();

        return response()->json(['success' => true, 'message' => 'Berhasil disimpan']);
    }
}
