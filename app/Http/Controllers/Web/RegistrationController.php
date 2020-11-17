<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Period;
use App\Models\Requirement;
use App\Models\Registrant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $period = Period::registration()->latest('ended_at')->first();

        $data = [
            'submission' => $user->registrants()->count(),
            'graduated' => $user->registrants()->where('status', 'Approve')->where('status','Lulus')->first(),
            'registered' => $user->registrants()->where('status', 'Approve')->first(),
            'progress' => $user->registrants()->where('status', 'Request')->first(),
            'rejected' => $user->registrants()->where('status', 'Reject')->get(),
            'progress' => $user->registrants()->where('status', 'Request')->first(),
            'cek_sertifikat' => $user->whereNotNull('no_sertifikat')->first(),
            'registrants' => $user->registrants()->with('university', 'requirement')->get(),
        ];

        return view('web.registration.index', $data);
    }

    public function register(Request $request)
    {
        $id = $request->university_id;

        return redirect()->route('web.registration.registration', ['requirement' => $id]);
    }

    public function registration(Requirement $requirement)
    {
        $check = Registrant::where('user_id', Auth::user()->id)->where('requirement_id',$requirement->id)->where('status','Request')->latest()->first();
        if(!empty($check)){
            return redirect()->route('web.registration.index');
        }
        $data = [
            'requirement' => $requirement,
        ];

        return view('web.registration.register', $data);
    }

    public function store(Request $request, Requirement $requirement)
    {
        $rules = [];
        $message = [];
        $universityRequirements = $requirement->items;
        foreach ($universityRequirements as $item)
        {
            $rule = [];

            if ($item->required && $item->type !== 'Checkbox') {
                $rule[] = 'required';
                $message['requirement_' . $item->id.'.required'] = 'Kolom "'.$item->name.'" harus diisi.';
            } else {
                $rule[] = 'nullable';
            }

            if ($item->type === 'File') {
                $rule[] = 'file';
            }

            $rules['requirement_' . $item->id] = implode('|', $rule);
        }

        $request->validate(
            $rules, $message
        );

        $user = Auth::user();

        $submission = Auth::user()->countSubmission();
        $university = $requirement->university;

        $registrant = $university->registrant()->create([
            'user_id' => $user->id,
            'requirement_id' => $requirement->id,
            'status' => 'Request',
            'submission' => $submission + 1
        ]);

        $items = [];
        foreach ($universityRequirements as $item)
        {
            $field = 'requirement_'.$item->id;

            if (($item->type === 'File') && $request->hasFile($field)) {

                $value = $request->file('requirement_'.$item->id)->store('requirement/'.$university->id.'/'.$user->id, 'local');

            } else if ($item->type === 'Checkbox' && $request->has('requirement_'.$item->id)) {

                $value = $request->input('requirement_'.$item->id) ? true : false;

            } else {

                $value = $request->has('requirement_'.$item->id) ? $request->input('requirement_'.$item->id) : null;

            }

            if ($value) {
                $items[] = [
                    'requirement_item_id' => $item->id,
                    'value' => $value
                ];
            }
        }

        $registrant->requirements()->createMany(
            $items
        );

        return response()->json(['success' => true, 'message' => 'Pendaftaran berhasil.']);
    }
}
