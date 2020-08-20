<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\RequirementGraduation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GraduationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $period = Period::registration()->latest('ended_at')->first();
        $user_university = $user->registrants->where('status','Approve')->first();
        $data = [
            'title' => 'Jadwal Pendaftaran Kelulusan',
            'breadcrumbs' => ['Home' => route('web.home'), 'Jadwal'],
            'registered' => $user->registrants_graduation()->where('status', 'Approve')->first(),
            'progress' => $user->registrants_graduation()->where('status', 'Request')->first(),
            'rejected' => $user->registrants_graduation()->where('status', 'Reject')->get(),
            'graduations' => $period ? RequirementGraduation::where(['status' => 'Active', 'period_id' => $period->id,'university_id' => $user_university->university_id])->latest()->paginate(10) : null
        ];

        return view('web.graduation.index', $data);
    }

    public function show(RequirementGraduation $requirement)
    {
        $data = [
            'title' => 'Pendaftaran Ujian Masuk - '.$requirement->university->name,
            'breadcrumbs' => ['Home', 'Jadwal' => route('web.graduation.index'), 'Pendaftaran'],
            'requirements' => $requirement->items,
        ];

        return view('web.graduation.show', $data);
    }
}
