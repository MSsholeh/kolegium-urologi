<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\Registrant;
use App\Models\RegistrantGraduation;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SelectController extends Controller
{
    public function period(Request $request, $type = 'registration')
    {
        $data = Period::select('id', DB::raw("CONCAT(name, ', ', started_at, ' s/d ',ended_at) as text"))
//            ->where('active', true)
            ->where('type', $type)
            ->orderBy('name');

        if ($request->keyword) $data->where('name', 'like', '%'.$request->keyword.'%');

        return response()->json([ 'items' => $data->get() ]);
    }

    public function userUniversitas(Request $request, $university)
    {
        $data = Registrant::where('registrants.university_id', $university)
            ->join('users', 'users.id', 'registrants.user_id')
            ->join('universities','universities.id','registrants.university_id')
            ->where('status', 'Approve')
            ->where('graduate', 'Lulus')
        ->select('registrants.id', DB::raw("CONCAT(users.npa, ' - ', users.name, ', ',universities.name) as text"));

        if ($request->keyword) $data->where('name', 'like', '%'.$request->keyword.'%');

        return response()->json([ 'items' => $data->get() ]);
    }

    public function userAdmin(Request $request)
    {
        $data = Registrant::join('users', 'users.id', 'registrants.user_id')
            ->join('universities','universities.id','registrants.university_id')
            ->where('status', 'Approve')
            ->where('graduate', 'Lulus')
        ->select('registrants.id', DB::raw("CONCAT(users.npa, ' - ', users.name, ', ',universities.name) as text"));

        if ($request->keyword) $data->where('name', 'like', '%'.$request->keyword.'%');

        return response()->json([ 'items' => $data->get() ]);
    }

    public function university(Request $request)
    {
        $data = University::select('id', 'name as text')
            ->orderBy('name');

        if ($request->keyword) $data->where('name', 'like', '%'.$request->keyword.'%');

        return response()->json([ 'items' => $data->get() ]);
    }

    public function user(Request $request)
    {
        $data = User::select('id', 'name as text')
            ->orderBy('name');

        if ($request->keyword) $data->where('name', 'like', '%'.$request->keyword.'%');

        return response()->json([ 'items' => $data->get() ]);
    }

    public function registrant(Request $request, $university)
    {
        $data = Registrant::where('registrants.university_id', $university)
            ->join('users', 'users.id', 'registrants.user_id')
            ->where('status', 'Approve')
            ->doesntHave('participate')
        ->select('registrants.id', 'users.name as text');

        if ($request->keyword) $data->where('name', 'like', '%'.$request->keyword.'%');

        return response()->json([ 'items' => $data->get() ]);
    }

    public function registrant_graduation(Request $request, $university)
    {
        $data = RegistrantGraduation::where('registrants_graduation.university_id', $university)
            ->join('users', 'users.id', 'registrants_graduation.user_id')
            ->where('status', 'Approve')
            ->doesntHave('participate')
        ->select('registrants_graduation.id', 'users.name as text');

        if ($request->keyword) $data->where('name', 'like', '%'.$request->keyword.'%');

        return response()->json([ 'items' => $data->get() ]);
    }
}
