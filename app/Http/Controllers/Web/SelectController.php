<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\University;
use App\Models\Requirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SelectController extends Controller
{
    public function period(Request $request)
    {
        $data = Period::select('id', DB::raw("CONCAT(name, ', ', started_at, ' s/d ',ended_at) as text"))
//            ->where('active', true)
            ->where('type', 'registration')
            ->orderBy('name');

        if ($request->keyword) $data->where('name', 'like', '%'.$request->keyword.'%');

        return response()->json([ 'items' => $data->get() ]);
    }

    public function university(Request $request)
    {
        $period = Period::registration()->latest('ended_at')->first();

        $data =  DB::table('requirements')
            ->join('universities', 'requirements.university_id', '=', 'universities.id')
            ->join('periods', 'requirements.period_id', '=', 'periods.id')
            ->where(['status' => 'Active', 'period_id' => $period->id])
            ->select('requirements.id', DB::raw("CONCAT(universities.name, ', ', periods.name) as text"));

        if ($request->keyword) $data->where('name', 'like', '%'.$request->keyword.'%');

        return response()->json([ 'items' => $data->get() ]);
    }

    public function certificate(Request $request)
    {
        $data = array(
            [
              "id" => 1,
              "text" => "Pengajuan Sertifikat Baru"
            ],
            [
              "id" => 2,
              "text" => "Pengajuan Sertifikat Ulang"
            ]
        );

        return response()->json([ 'items' => $data ]);
    }
}
