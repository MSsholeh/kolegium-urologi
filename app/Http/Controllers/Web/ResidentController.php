<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Registrant;
use App\Models\University;
use Illuminate\Support\Facades\Auth;

class ResidentController extends Controller
{
    public function index()
    {

        $data = [
            'universities' => University::All(),
            'query' => Registrant::select('*', 'registrants.status as status_registrant', 'registrants.id as primary')
            ->with('user', 'university', 'requirement.period'),
        ];

        return view('web.resident.index', $data);
    }
}
