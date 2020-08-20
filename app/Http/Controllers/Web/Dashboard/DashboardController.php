<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CourseParticipant;
use App\Models\ExamParticipant;
use App\Models\Registrant;
use App\Models\Requirement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $data = [
            'title' => 'Dashboard',
            'breadcrumbs' => ['Home' => route('web.home'), 'Dashboard'],
            'user' => $user,
            'registrants' => $user->registrants()->with('university', 'requirement')->get(),
//            'submission' => $user->registrants()->count(),
//            'registered' => $user->registrants()->where('status', 'Approve')->first(),
//            'progress' => $user->registrants()->where('status', 'Request')->first(),
//            'requirements' => Requirement::where('status', 'Active')->with(['university', 'registrants' => static function($query) use($user) { $query->where('user_id', $user->id); }])->paginate(10)
        ];

        return view('web.dashboard.index', $data);
    }

    public function detail($id)
    {
        $data = [
            'title' => 'Status Pendaftaran',
            'breadcrumbs' => ['Home' => route('web.home'), 'Dashboard' => route('web.dashboard.home'), 'Status Pendaftaran'],
            'registrant' => Registrant::where('id', $id)->with('requirements.item')->first()
        ];

        return view('web.dashboard.detail', $data);
    }

    public function profile()
    {
        $data = [
            'title' => 'Profile',
            'breadcrumbs' => ['Home' => route('web.home'), 'Dashboard' => route('web.dashboard.home'), 'Profile'],
            'user' => Auth::user()
        ];

        return view('web.dashboard.profile', $data);
    }
}
