<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\Registrant;
use App\Models\Requirement;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index($periodId = null)
    {
        $data = [
            'users' => User::count(),
            'approved' => Registrant::where('status', 'Approve')->when($periodId, static function($query) use ($periodId) {
                $query->whereHas('requirement', function($queryRequirement) use($periodId) {
                    $queryRequirement->where('period_id', $periodId);
                });
            })->count(),
            'rejected' => Registrant::where('status', 'Reject')->when($periodId, static function($query) use ($periodId) {
                $query->whereHas('requirement', function($queryRequirement) use($periodId) {
                    $queryRequirement->where('period_id', $periodId);
                });
            })->count(),
            'process' => Registrant::where('status', 'Request')->when($periodId, static function($query) use ($periodId) {
                $query->whereHas('requirement', function($queryRequirement) use($periodId) {
                    $queryRequirement->where('period_id', $periodId);
                });
            })->count(),
            'university' => Requirement::where('status', 'Active')->when($periodId, static function($query) use ($periodId) {
                $query->where('period_id', $periodId);
            })->count(),
            'periods' => Period::registration()->get(),
            'periodFilter' => $periodId ? Period::find($periodId) : null
        ];

        return view('admin.index', $data);
    }
}
