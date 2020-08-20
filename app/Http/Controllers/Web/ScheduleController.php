<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\Requirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $period = Period::registration()->latest('ended_at')->first();

        $data = [
            'title' => 'Jadwal Pendaftaran PPDS',
            'breadcrumbs' => ['Home' => route('web.home'), 'Jadwal'],
            'submission' => $user->registrants()->count(),
            'registered' => $user->registrants()->where('status', 'Approve')->first(),
            'progress' => $user->registrants()->where('status', 'Request')->first(),
            'rejected' => $user->registrants()->where('status', 'Reject')->get(),
            'schedules' => $period ? Requirement::with('university')->where(['status' => 'Active', 'period_id' => $period->id])->latest()->paginate(10) : null
        ];

        return view('web.schedule.index', $data);
    }

    public function show(Requirement $requirement)
    {
        $data = [
            'title' => 'Pendaftaran Ujian Masuk - '.$requirement->university->name,
            'breadcrumbs' => ['Home', 'Jadwal' => route('web.schedule.index'), 'Pendaftaran'],
            'requirements' => $requirement->items,
        ];

        return view('web.schedule.show', $data);
    }
}
