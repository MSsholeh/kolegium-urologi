<?php

namespace App\Http\Controllers\Web\Schedule;

use App\Helpers\Daster;
use App\Http\Controllers\Controller;
use App\Models\CourseParticipant;
use App\Models\CourseSchedule;
use App\Models\ParticipantCourseRequirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    private $route;
    private $title;

    public function __construct()
    {
        $this->title = 'Kursus';
        $this->route = 'web.schedule.course';
    }

    public function show(CourseSchedule $schedule)
    {
        $participants = $schedule->participants;
        $registered = Auth::check() && Auth::user() ? $participants->where('user_id', Auth::user()->id)->sortByDesc('submission')->first() : null;

        $data = [
            'title' => $this->title,
            'breadcrumbs' => [
                'Jadwal Ujian & Kursus' => route('web.schedule.index'),
                $schedule->course->name.' '.$schedule->event_date_format => route('web.schedule.course.show', $schedule->id)
            ],
            'schedule' => $schedule,
            'participant' => $participants->count(),
            'registered' => $registered,
            'requirements' => $registered ?
                $schedule->participants()
                    ->where('user_id', Auth::user()->id)
                    ->latest('submission')->with('requirements.requirement')->first() : '',
            'quotaPercent' => $schedule->getRemainingQuotaPercent(),
            'approved' => ($registered && $registered->status === 'Diterima')
        ];

        return view($this->route.'.show', $data);
    }

    public function register(CourseSchedule $schedule)
    {
        $data = [
            'title' => $this->title,
            'breadcrumbs' => [
                'Jadwal Ujian & Kursus' => route('web.schedule.index'),
                $schedule->course->name.' '.Daster::tanggal($schedule->event_date) => route('web.schedule.course.show', $schedule->id),
                'Daftar'
            ],
            'schedule' => $schedule,
            'requirements' => $schedule->requirements
        ];

        return view($this->route.'.register', $data);
    }

    public function store(Request $request, CourseSchedule $schedule)
    {
        $rules = [];
        $message = [];
        $scheduleRequirements = $schedule->requirements;
        foreach ($scheduleRequirements as $requirement)
        {
            $rule = [];

            if ($requirement->required && $requirement->type !== 'Checkbox') {
                $rule[] = 'required';
                $message['requirement_' . $requirement->id.'.required'] = 'Kolom "'.$requirement->name.'" harus diisi.';
            } else {
                $rule[] = 'nullable';
            }

            if ($requirement->type === 'File') {
                $rule[] = 'file';
            }

            $rules['requirement_' . $requirement->id] = implode('|', $rule);
        }

        $request->validate(
            $rules, $message
        );

        $user = Auth::user();

        $participant = $schedule->participants()->create([
            'user_id' => $user->id,
            'status' => 'Baru',
            'submission' => 1
        ]);

        $requirements = [];
        foreach ($scheduleRequirements as $requirement)
        {
            $field = 'requirement_'.$requirement->id;

            if (($requirement->type === 'File') && $request->hasFile($field)) {

               $value = $request->file('requirement_'.$requirement->id)->store('requirement/'.$schedule->id.'/'.$user->id, 'local');

            } else if ($requirement->type === 'Checkbox' && $request->has('requirement_'.$requirement->id)) {

                $value = $request->input('requirement_'.$requirement->id) ? true : false;

            } else {

                $value = $request->has('requirement_'.$requirement->id) ? $request->input('requirement_'.$requirement->id) : null;

            }

            if ($value) {
                $requirements[] = [
                    'course_requirement_id' => $requirement->id,
                    'value' => $value
                ];
            }
        }

        $participant->requirements()->createMany(
            $requirements
        );

        return response()->json(['success' => true, 'message' => 'Pendaftaran berhasil.']);

    }

    public function submitted(CourseSchedule $schedule)
    {
        $data = [
            'title' => $this->title,
            'breadcrumbs' => [
                'Jadwal Ujian & Kursus' => route('web.schedule.index'),
                $schedule->course->name.' '.Daster::tanggal($schedule->event_date_format) => route('web.schedule.course.show', $schedule->id),
                'Daftar',
                'Simpan'
            ],
            'schedule' => $schedule,
        ];

        return view($this->route.'.submitted', $data);
    }

    public function subject(CourseSchedule $schedule)
    {
        $data = [
            'title' => $this->title,
            'breadcrumbs' => [
                'Jadwal Ujian & Kursus' => route('web.schedule.index'),
                $schedule->course->name.' '.Daster::tanggal($schedule->event_date_format) => route('web.schedule.course.show', $schedule->id),
                'Materi',
            ],
            'schedule' => $schedule,
            'subjects' => $schedule->subjects()->paginate(10)
        ];

        return view($this->route.'.subject', $data);
    }
}
