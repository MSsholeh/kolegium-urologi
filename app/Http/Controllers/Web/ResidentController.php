<?php

namespace App\Http\Controllers\Web;

use App\Helpers\Daster;
use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\Registrant;
use App\Models\RegistrantGraduation;
use App\Models\ExamParticipant;
use App\Models\User;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Ui\Presets\React;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Crypt;
use Session;


class ResidentController extends Controller
{
    public function index()
    {

        $data = [
            'universities' => University::All(),
            'query' => Registrant::with('user', 'university', 'requirement.period','participate')
            ->whereDoesntHave('participate', function($q){
                $q->where('graduate','Lulus');
            })->get(),
        ];

        return view('web.resident.index', $data);
    }
}
