<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SelectController extends Controller
{
    public function period(Request $request)
    {
        $data = Period::select('id', DB::raw("CONCAT(name, ', ', started_at, ' s/d ',ended_at) as text"))
//            ->where('active', true)
            ->orderBy('name');

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
}
