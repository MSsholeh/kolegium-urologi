<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\RegistrantGraduation;
use App\Models\ExamParticipant;
use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::check()){
            $graduation =RegistrantGraduation::where('user_id',auth()->user()->id)->where('status','Approve')->first();

            if(!empty($graduation)) { $lulus = ExamParticipant::where('registrant_graduation_id', $graduation->id)->where('graduate','Lulus')->first(); }

            if(!empty($lulus)){
                return redirect('certificate');
            }else{
                return redirect('registration');
            }
        }
        return redirect('register');
    }
}
