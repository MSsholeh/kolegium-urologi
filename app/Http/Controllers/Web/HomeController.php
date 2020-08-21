<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ExamSchedule;
use App\Models\Period;
use App\Models\Requirement;
use App\Models\University;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $period = Period::registration()->latest('ended_at')->first();
        $universities = $period ? University::with(['registrant' => function($query) use($period) {
            $query->whereHas('requirement', function($queryRequirement) use($period) {
                $queryRequirement->where('period_id', $period->id);
            });
        }])->get() : null;

        $examPeriod = Period::exam()->latest('ended_at')->first();
        $exams = $examPeriod ? University::with(['registrant' => function($query) use($examPeriod) {
            $query->whereHas('requirement')
            ->whereHas('participate.examSchedule', function($queryParticipate) use($examPeriod) {
                $queryParticipate->where('period_id', $examPeriod->id);
            })
            ->with('user.university', 'participate');
        }, 'registrant.user'])->get() : null;

        $data = [
            'breadcrumbs' => ['Home' => route('web.home')],
            'examPeriod' => $examPeriod,
            'period' => $period,
            'universities' => $universities,
            'exams' => $exams
        ];

        return view('web.home', $data);
    }
}
