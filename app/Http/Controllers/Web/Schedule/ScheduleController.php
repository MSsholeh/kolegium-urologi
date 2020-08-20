<?php

namespace App\Http\Controllers\Web\Schedule;

use App\Http\Controllers\Controller;
use App\Models\CourseSchedule;
use App\Models\ExamSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    protected $title;

    public function __construct()
    {
        $this->title = 'Jadwal Ujian & Kursus';
    }

    public function index()
    {
        $data = [
            'title' => 'Jadwal',
            'breadcrumbs' => [$this->title => route('web.schedule.index')],
            'examSchedules' => ExamSchedule::latest('event_date')->with('exam')->paginate(10),
            'courseSchedules' => CourseSchedule::latest('started_at')->with('course')->paginate(10),
        ];

        return view('web.schedule.index', $data);
    }
}
