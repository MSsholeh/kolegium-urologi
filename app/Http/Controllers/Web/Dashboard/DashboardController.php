<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $graduation = App\Models\RegistrantGraduation::where('user_id',auth()->user()->id)->where('status','Approve')->first();

        if(!empty($graduation)) { $lulus = App\Models\ExamParticipant::where('registrant_graduation_id', $graduation->id)->where('graduate','Lulus')->first(); }

        if(!empty($lulus)){
            return redirect('certificate');
        }else{
            return redirect('registration');
        }
    }
}
